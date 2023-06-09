<?php

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
Route::group(['middleware' => ['https']], function () {
    /*
    *   Cart routes
    */
    Route::get('/carrinho/{product_id}/add/', 'CartController@addToCart')->name('cart.fastAdd');
    Route::get('/carrinho/add/', 'CartController@addToCart')->name('cart.add');
    Route::get('/carrinho/delete', 'CartController@clearCart')->name('cart.clear');
    Route::get('/carrinho', 'CartController@show')->name('user.cart');
    Route::get('/carrinho/{product_id}/remove', 'CartController@removeFromCart')->name('cart.remove');
    Route::get('/carrinho/{product_rowId}/{qty}/{product_id}', 'CartController@changeQuantity');

    Route::get('/docs', function () {
        return view('docs');
    })->name('docs');

    /* 
    *   Autentificação - Login - Rede Social
    */
    Auth::routes();
    Route::get('/auth/{provider}', 'Auth\LoginController@redirectToProvider')->name('loginSocial');
    Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

    /* 
    *   Pág. inicial 
    */
    Route::get('/', 'IndexController@index')->name('landing');

    /*
    *   Suporte
    */
    Route::group(['prefix' => 'support'], function () {
        Route::get('/index', function () {
            return view('support.index');
        })->name('support.index');
    });


    /* 
    *   Barra de pesquisa de produtos
    */
    Route::get('/find', 'SearchController@find')->name('search');

    /*
    *   View produto
    */
    Route::get('/produto/{id}', 'ProductController@show')->name('product.show');


    Route::get('/explore', 'OfertasController@viewExplore')->name('explore');
    Route::get('/ofertas', 'OfertasController@view')->name('offers');
    Route::get('/customize/quarto', 'OfertasController@viewCustomize')->name('customizeQuarto');

    /*
    *   Checar CEP
    */
    Route::get('/checkout/address/cep', 'CheckoutController@checkCep')->name('checkCep');
    Route::get('/cupom/validate/{cupom_name}', 'CheckoutController@cupomValidate');




    /*
    *   Calcular CEP
    */
    Route::get('/calc/frete', 'CheckoutController@calcFrete')->name('calcFrete');

    Route::post('/buy/now', 'ProductController@buyNow')->name('comprarAgora');

    Route::group(['middleware' => ['auth']], function () {
        /*
        *   User views
        */
        Route::get('/perfil', 'UserController@showProfile')->name('perfil');
        Route::post('/perfil/update', 'UserController@updateProfile')->name('perfilUpdate');
        Route::get('/pedidos', 'OrderController@show')->name('orders');
        Route::post('/avaliar', 'UserController@avaliate')->name('avaliate');

        Route::post('/excluir/conta', 'UserController@deletarConta')->name('deletarConta');

        Route::get('/delete/address', 'UserController@deleteAddressSave')->name('deleteAddressSave');

        Route::get('/get/saved/address', 'UserController@getAddressSaved');

        /*
        *   Limpar histórico
        */
        Route::get('/historico/{id}/clean', 'HistoricController@destroy');

        /*
        *   Checkout
        */
        Route::group(['middleware' => ['Checkout']], function () {
            Route::get('/checkout/endereco', 'CheckoutController@adressCheckout')->name('address-check');
            Route::post('/checkout/address/data', 'CheckoutController@addressData')->name('address-data');
            Route::group(['middleware' => ['CheckoutPayment']], function () {
                Route::get('/checkout/pagamento', 'CheckoutController@paymentCheckout')->name('payment-check');
                Route::post('/checkout/payment/data', 'CheckoutController@paymentData');

                /*
                *   PayPal
                */
                Route::post('/create-payment', 'PaymentController@create')->name('create-payment');
                Route::get('/execute-payment', 'PaymentController@execute');
                Route::get('/cancel-payment', 'PaymentController@cancel');
            });
        });
        Route::get('/paypal/transaction/complete', 'CheckoutController@paymentSuccess');

        /*
        *   Order
        */
        Route::get('/pedido/cancel/{order_id}', 'OrderController@cancel')->name('order.cancel');
        Route::get('/pedido/rastrear', 'OrderController@track');
        Route::get('/pedido/produtos', 'OrderController@showOrderProducts')->name('showOrderProducts');

        /*
        *   Tickets
        */
        Route::get('/tickets', 'TicketController@index')->name('tickets');
        Route::get('/ticket/create', 'TicketController@create')->name('ticket.create');
        Route::post('/ticket/store', 'TicketController@store')->name('ticket.store');


        /*
        * Product Ratings
        */
        Route::get('/rating/index', 'RatingController@index')->name('rating.index');
        Route::get('/rating/create', 'RatingController@create')->name('rating.create');
        Route::post('/rating/store', 'RatingController@store')->name('rating.store');
        Route::get('/rating/edit/{rating_id}', 'RatingController@edit')->name('rating.edit');
        Route::post('/rating/update', 'RatingController@update')->name('rating.update');
        route::get('/rating/destroy/{rating_id}', 'RatingController@destroy')->name('rating.destroy');
        Route::get('/product/rating', 'RatingController@productRating');
    });

    /*
    *   Admin
    */
    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
        /*
        *   Landing page admin 
        */
        Route::get('/', 'AdminController@index')->name('admin.index');

        /*
        *   Produtos 
        */
        Route::get('/products', 'AdminController@products')->name('admin.products');
        Route::get('/product/{product_id}/destroy', 'ProductController@destroy');
        Route::get('/product/{product_id}/edit', 'ProductController@formEdit');
        Route::post('/product/edit/data', 'ProductController@update')->name('admin.product.update');
        Route::get('/product/create', 'ProductController@create')->name('admin.createProduct');
        Route::post('/product/create/data', 'ProductController@store')->name('admin.product.store');
        Route::get('/product/{product_id}/desconto', 'AdminController@ofertaProdutoView');
        Route::post('/product/desconto', 'AdminController@ofertaProduto')->name('product.discount');

        Route::get('/category/desconto', 'AdminController@ofertaCategoriaView')->name('category.discount.show');
        Route::post('/category/desconto/aplicar', 'AdminController@ofertaCategoria')->name('category.discount.apply');

        Route::get('/cupom', 'AdminController@cupomView')->name('admin.cupons');
        Route::get('/cupom/create', 'AdminController@cupomCreate')->name('admin.cupom.create');
        Route::post('/cupom/store', 'AdminController@cupomStore')->name('admin.cupom.store');
        Route::get('/cupom/destroy/{cupom_id}', 'AdminController@cupomDestroy')->name('admin.cupom.destroy');

        /*
        *   Pedidos 
        */
        Route::get('/orders', 'AdminController@orders')->name('admin.orders');
        Route::get('/order/{id}/cancel', 'OrderController@cancel')->name('admin.order.cancel');
        Route::get('/order/{id}/entregue', 'OrderController@pedidoEntregue')->name('admin.order.entregue');
        Route::get('/order/{id}/despachado', 'OrderController@pedidoDespachado')->name('admin.order.despachado');
        Route::get('/order/{id}/aprovado', 'OrderController@pedidoAprovado')->name('admin.order.despachado');

        /*
        *   Tickets
        */
        Route::get('/ticket/edit/{ticket_id}', 'TicketController@edit')->name('admin.ticket');
        Route::post('/ticket/update', 'TicketController@response')->name('admin.ticket.update');
    });



    Route::get('/test-components', function () {
        return view('test_components');
    });
});
