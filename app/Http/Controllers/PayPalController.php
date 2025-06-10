<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
        );
        
        $this->apiContext->setConfig([
            'mode' => config('services.paypal.settings.mode'),
            'http.ConnectionTimeOut' => 3000,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('/logs/paypal.log'),
            'log.LogLevel' => 'DEBUG'
        ]);
    }

    public function createPayment()
    {
        try {
            // Validar y obtener carrito correctamente
            $cart = $this->getValidCart();
            
            if (empty($cart)) {
                return redirect()->route('cart.index')
                               ->with('error', 'Tu carrito está vacío');
            }

            // Calcular total
            $total = $this->calculateCartTotal($cart);

            // Configurar pago
            $payment = $this->createPayPalPayment($total);

            // Crear pago y redirigir
            $payment->create($this->apiContext);
            return redirect()->away($payment->getApprovalLink());

        } catch (\Exception $ex) {
            Log::error('Error PayPal createPayment: '.$ex->getMessage());
            return redirect()->route('cart.index')
                           ->with('error', 'Error al procesar el pago: '.$ex->getMessage());
        }
    }

    public function executePayment(Request $request)
{
    \Log::info('Iniciando executePayment', $request->all());

    try {
        $paymentId = $request->input('paymentID');
        $payerId = $request->input('payerID');

        if (empty($paymentId) || empty($payerId)) {
            throw new \Exception('Parámetros de pago faltantes');
        }

        // Configura el contexto de PayPal
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
        );
        $apiContext->setConfig([
            'mode' => config('services.paypal.mode', 'sandbox')
        ]);

        // Obtiene y ejecuta el pago
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        
        $result = $payment->execute($execution, $apiContext);
        \Log::info('Resultado de PayPal:', ['state' => $result->getState()]);

        if ($result->getState() === 'approved') {
            // Procesa el pedido exitoso
            $order = $this->processSuccessfulPayment($payment);
            
            return response()->json([
                'success' => true,
                'redirect' => route('gracias', ['order_id' => $order->id]),
                'message' => 'Pago completado exitosamente'
            ]);
        }

        throw new \Exception('El pago no fue aprobado por PayPal');

    } catch (\Exception $e) {
        \Log::error('Error en executePayment: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar el pago: ' . $e->getMessage()
        ], 500);
    }
}

private function processSuccessfulPayment($payment)
{
    // Obtiene el carrito de la sesión
    $cart = session()->get('cart', []);
    $total = 0;

    // Calcula el total y valida los productos
    foreach ($cart as $id => $item) {
        $product = Producto::findOrFail($id);
        $total += $product->precio * $item['cantidad'];
    }

    // Crea la orden
    $order = Order::create([
        'user_id' => auth()->id(),
        'total' => $total,
        'payment_id' => $payment->getId(),
        'payment_method' => 'PayPal',
        'status' => 'completed'
    ]);

    // Crea los items de la orden
    foreach ($cart as $id => $item) {
        $product = Producto::findOrFail($id);
        
        OrderItem::create([
            'order_id' => $order->id,
            'producto_id' => $id,
            'quantity' => $item['cantidad'],
            'price' => $product->precio
        ]);

        // Actualiza el stock
        $product->decrement('stock', $item['cantidad']);
    }

    // Limpia el carrito
    session()->forget('cart');

    return $order;
}
    /***********************
     * Métodos Auxiliares *
     ***********************/

    /**
     * Obtiene el carrito validado
     */
    private function getValidCart()
    {
        $cart = session('cart');
        
        // Si el carrito es string (serializado), convertirlo a array
        if (is_string($cart)) {
            try {
                $cart = json_decode($cart, true);
            } catch (\Exception $e) {
                Log::error('Error decodificando carrito: '.$e->getMessage());
                $cart = [];
            }
        }
        
        // Asegurarse de que sea un array
        return is_array($cart) ? $cart : [];
    }

    private function calculateCartTotal($cart)
    {
        $total = 0;
        
        // Verificar que $cart sea iterable
        if (!is_array($cart) && !$cart instanceof \Countable) {
            throw new \Exception('El carrito no contiene datos válidos');
        }
        
        foreach ($cart as $id => $item) {
            // Validar estructura del item
            if (!is_array($item) || !isset($item['cantidad']) || !isset($item['precio'])) {
                Log::warning("Item de carrito inválido: ".print_r($item, true));
                continue;
            }
            
            $producto = Producto::find($id);
            if (!$producto) {
                throw new \Exception("El producto ID $id ya no existe");
            }
            
            if ($producto->stock < $item['cantidad']) {
                throw new \Exception("Stock insuficiente para {$producto->nombre}");
            }
            
            $total += $producto->precio * $item['cantidad'];
        }
        
        return number_format($total, 2, '.', '');
    }

    private function createPayPalPayment($total)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setCurrency("MXN")
               ->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                   ->setDescription("Compra en ".config('app.name'));

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.execute'))
                    ->setCancelUrl(route('cart.index'));

        $payment = new Payment();
        $payment->setIntent("sale")
               ->setPayer($payer)
               ->setRedirectUrls($redirectUrls)
               ->setTransactions([$transaction]);

        return $payment;
    }


}