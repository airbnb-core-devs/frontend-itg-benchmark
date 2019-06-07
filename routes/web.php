<?php

use Doomus\User;
use Doomus\Historic;
use Doomus\Order;
use Doomus\CartProduct;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* 
* Rotas da autentificação
*/
Auth::routes();

/* 
* Rota da página inicial 
*/
Route::get('/', 'IndexController@index')->name('landing');

Route::get('/find', 'SearchController@find')->name('search');

Route::get('/produto/{id}', 'ProductController@show');

Route::group(['middleware' => ['auth']], function (){
    /*
     * User views
     * */
    Route::get('/perfil', 'UserController@showProfile')->name('perfil');
    Route::post('/perfil/update', 'UserController@updateProfile');
    Route::get('/pedidos', 'OrderController@show');
    Route::get('/historico', 'HistoricController@show');

    /*
     * Checkout
     * */
    Route::get('/checkout/endereco', 'CheckoutController@adressCheckout')->name('adress-check');
    Route::get('/checkout/pagamento', 'CheckoutController@paymentCheckout')->name('payment-check');
    Route::post('/checkout/address/data', 'CheckoutController@addressData');
    Route::post('/checkout/payment/data', 'CheckoutController@paymentData');
    Route::get('/sucesso', 'CheckoutController@success');

    /*
     * Order
     * */
    Route::get('/pedido/cancel', 'OrderController@cancel');
    Route::get('/pedido/rastrear', 'OrderController@track');

    /*
     * Support page
     * */
    Route::get('/suporte', 'SuporteController@show')->name('suporte');
});

/*
 * Cart routes
 * */
Route::get('/carrinho/{product_id}/add/', 'CartController@addToCart');
Route::get('/carrinho/add/', 'CartController@addToCart')->name('cart.add');
Route::get('/carrinho/delete', 'CartController@clearCart')->name('cart.clear');
Route::get('/carrinho', 'CartController@show')->name('user.cart');

/*
* Social login routes
**/
Route::get('/auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');