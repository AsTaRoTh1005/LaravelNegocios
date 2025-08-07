<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TwitterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DiscordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('registerr');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/login/twitter', [TwitterController::class, 'redirectToTwitter'])->name('login.twitter');
Route::get('/login/twitter/callback', [TwitterController::class, 'handleTwitterCallback']);


Route::get('/login/discord', [DiscordController::class, 'redirectToDiscord'])->name('login.discord');
Route::get('/login/discord/callback', [DiscordController::class, 'handleDiscordCallback']);
Route::get('/login/github', [GitHubController::class, 'redirectToGitHub'])->name('login.github');
Route::get('/login/github/callback', [GitHubController::class, 'handleGitHubCallback']);

Route::middleware('auth')->group(function () {

    Route::middleware('role:Cliente')->group(function () {     
        Route::get('/cliente/Inicio', [AuthController::class, 'clienteHome'])->name('homeCliente');
        Route::get('/cliente/sobreNosotros', [AuthController::class, 'sobreNosotros'])->name('sobreNosotros');
        Route::post('/enviar-consulta', [ContactoController::class, 'enviarConsulta'])->name('enviar.consulta');
        Route::get('/cliente/Mapa', [AuthController::class, 'mapa'])->name('mapa');
        
        Route::get('/cliente/Contactanos', [AuthController::class, 'contactanosC'])->name('contactanosC');
        Route::get('/cliente/negocios', [NegocioController::class, 'clienteIndex'])->name('cliente.negocios');
        Route::get('/negocios/{id}/productos', [NegocioController::class, 'getProductosByNegocio']);
        
Route::get('/cliente/negocios/{negocio}', [NegocioController::class, 'clienteShow'])->name('cliente.negocios.show');
// Rutas del carrito
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});
Route::get('/gracias', [PayPalController::class, 'gracias'])->name('gracias');
Route::get('/paypal/create', [PayPalController::class, 'createPayment'])->name('paypal.create');
Route::get('/paypal/execute', [PayPalController::class, 'executePayment'])->name('paypal.execute');
});
    
    Route::middleware('role:Administrador')->group(function () {
        Route::get('/admin/Inicio', [AuthController::class, 'adminHome'])->name('homeAdmin');
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios/store', [UserController::class, 'store'])->name('usuario.store');
        Route::get('/usuarios/{id}', [UserController::class, 'show'])->name('usuario.show');
        Route::put('/usuarios/update/{id}', [UserController::class, 'update'])->name('usuario.update');
        Route::delete('/usuarios/delete/{id}', [UserController::class, 'destroy'])->name('usuario.destroy');
        Route::get('/negocios', [NegocioController::class, 'index'])->name('negocio.index');
            Route::post('/negocios/store', [NegocioController::class, 'store'])->name('negocio.store');
            Route::get('/negocios/{id}', [NegocioController::class, 'show'])->name('negocio.show');
            Route::put('/negocios/update/{id}', [NegocioController::class, 'update'])->name('negocio.update');
            Route::delete('/negocios/delete/{id}', [NegocioController::class, 'destroy'])->name('negocio.destroy');
        });

    Route::middleware('role:Vendedor')->group(function () {
        
        Route::get('/vendedor/Inicio', [AuthController::class, 'vendedorHome'])->name('homeVendedor');
        Route::get('/vendedor/Contactanos', [AuthController::class, 'contactanosV'])->name('contactanosV');
        Route::post('/enviar-consulta', [ContactoController::class, 'enviarConsulta'])->name('enviar.consulta');   

        Route::get('/productos', [ProductoController::class, 'index'])->name('producto.index');
        Route::post('/productos/store', [ProductoController::class, 'store'])->name('producto.store');
        Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('producto.show');
        Route::put('/productos/update/{id}', [ProductoController::class, 'update'])->name('producto.update');
        Route::delete('/productos/delete/{id}', [ProductoController::class, 'destroy'])->name('producto.destroy');
            Route::get('/vendedor/negocio', [NegocioController::class, 'vendedorShow'])->name('vendedor.negocio');
            Route::put('/vendedor/negocio/actualizar', [NegocioController::class, 'vendedorUpdate'])->name('vendedor.negocio.update');
        
    });
});