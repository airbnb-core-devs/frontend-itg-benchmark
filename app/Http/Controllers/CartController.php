<?php

namespace Doomus\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Doomus\Cart as CartModel;
use Illuminate\Support\Facades\Auth;
use Doomus\Http\Requests\AddToCart;
use Doomus\Http\Controllers\UserController as User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Doomus\Product;

class CartController extends Controller
{
    public function show(){
        Session::forget('cupom');
        return view('cart');
    }

    public static function addToCartBuyNow($product_id) {
        $product = Product::find($product_id);

        if(1 > $product->qtd_restante){
            Session::flash('status', "Desculpe, nosso estoque está esgotado!");
            Session::flash('status-type', 'danger');
            return redirect('/');
        }

        Cart::add($product_id, $product->nome, 1, $product->valor)->associate('Product');

        return redirect('/checkout/endereco');
    }
    
    /**
     * Add to cart
     *
     * @param $product_id
     * @return \Illuminate\Http\Response
     */
    public function addToCart(AddToCart $request, $product_id = null, $qty = null)
    {
        $product = $product_id !== null ? Product::find($product_id) : Product::find($request->get('product_id'));

        if($request->get('qty') !== null && $request->get('qty') > $product->qtd_restante){
            Session::flash('status', "Desculpe, nós só temos mais $product->qtd_restante restante desse produto no estoque..
                Adicione até esse valor!");
            Session::flash('status-type', 'danger');
            return back();
        }

        if($product_id !== null){

            $name = $product->nome;
            $qtd = 1;
            $price = $product->valor;

            Cart::add($product_id, $name, $qtd, $price)->associate('Product');

            Session::flash('status', 'Produto adicionado ao carrinho!');

            return back();    
        }

        $qtd = $request->get('qty');

        $name = $product->nome;
        $price = $product->valor;

        Cart::add($request->get('product_id'), $name, $qtd, $price)->associate('Product');

        Session::flash('status', 'Produto adicionado ao carrinho!');
        return back();
    }
    
    /**
     * Remove from cart
     *
     * @param Product $product_id
     * @return \Illuminate\Http\Response
     */
    public function removeFromCart($product_id)
    {
        Cart::remove($product_id);

        Session::flash('status', 'Produto removido do carrinho');
        Session::flash('status-type', 'danger');
        return back();
    }

    /**
     * Change quantity
     *
     * @param Product $product_id
     * @param Product $qty
     * @return \Illuminate\Http\Response
     */
    public function changeQuantity($product_rowId, $qty, $product_id)
    {
        $product = Product::find($product_id);

        if($qty > $product->qtd_restante){
            Session::flash('status', "Desculpe, nós só temos mais $product->qtd_restante restante desse produto no estoque..
                Adicione até esse valor!");
            Session::flash('status-type', 'danger');
            return response()->json(['textStatus' => 'error']);
        }
        
        Cart::update($product_rowId, $qty);

        return back();
    }

    public function clearCart(){
        Cart::destroy();

        Session::flash('status', 'Carrinho limpo');
        return back();
    }
}
