<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Facades\PayPal;

class PagoController extends Controller
{
    public function pagar(Request $request)
    {
        $paypal = PayPal::setProvider();
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();

        // Crear una orden de pago
        $response = $paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->precio
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel')
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('cliente.negocios')->with('error', 'Error al procesar el pago.');
        }
    }

    public function success(Request $request)
    {
        $paypal = PayPal::setProvider();
        $paypal->setApiCredentials(config('paypal'));
        $paypal->getAccessToken();
        $response = $paypal->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == "COMPLETED") {
            return redirect()->route('cliente.negocios')->with('success', 'Pago realizado con éxito.');
        } else {
            return redirect()->route('cliente.negocios')->with('error', 'No se pudo completar el pago.');
        }
    }

    public function cancel()
    {
        return redirect()->route('cliente.negocios')->with('error', 'Pago cancelado.');
    }
}
