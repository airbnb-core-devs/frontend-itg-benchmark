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

        if(1 > $product->qtd_last){
            Session::flash('status', "Desculpe, nosso estoque está esgotado!");
            Session::flash('status-type', 'danger');
            return redirect('/');
        }

        Cart::add($product_id, $product->name, 1, $product->price)->associate('Product');

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

        if($request->get('qty') !== null && $request->get('qty') > $product->qtd_last){
            Session::flash('status', "Desculpe, nós só temos mais $product->qtd_last restante desse produto no estoque..
                Adicione até esse valor!");
            Session::flash('status-type', 'danger');
            return back();
        }

        if($product_id !== null && $product->discount !== null){

            $name = $product->name;
            $qtd = 1;
            $price = $product->price - ($product->price * $product->discount);

            Cart::add($product_id, $name, $qtd, $price)->associate('Product');

            Session::flash('status', 'Produto adicionado ao carrinho!');

            return back();    
        }

        if ($product_id !== null) {
            $name = $product->name;
            $qtd = 1;
            $price = $product->price;

            Cart::add($product_id, $name, $qtd, $price)->associate('Product');

            Session::flash('status', 'Produto adicionado ao carrinho!');

            return back();    
        }

        if ($product->discount !== null) {
            $qtd = $request->get('qty');

            $name = $product->name;
            $price = $product->price - ($product->price * $product->discount);
    
            Cart::add($request->get('product_id'), $name, $qtd, $price)->associate('Product');
    
            Session::flash('status', 'Produto adicionado ao carrinho!');
            return back();
        } 
        
        if ($product->discount === null) {
            $qtd = $request->get('qty');
    
            $name = $product->name;
            $price = $product->price;
    
            Cart::add($request->get('product_id'), $name, $qtd, $price)->associate('Product');
    
            Session::flash('status', 'Produto adicionado ao carrinho!');
            return back();
        }

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
    public function changeQuantity(Request $request, $product_rowId, $qty, $product_id)
    {
        $product = Product::find($product_id);

        if($qty > $product->qtd_last){
            if ($request->ajax()) {
                $response = array(
                    'status' => 'error',
                    'message' => "Desculpe, nós só temos mais $product->qtd_last restante desse produto no estoque..
                    Adicione até esse valor!"
                );

                return response()->json($response);
            }

            Session::flash('status', "Desculpe, nós só temos mais $product->qtd_last restante desse produto no estoque..
                Adicione até esse valor!");
            Session::flash('status-type', 'danger');

            
            return back();
        }

        if ($request->ajax()) {
            $response = array(
                'status' => 'success',
                'message' => 'Quantidade atualizada com successo'
            );

            Cart::update($product_rowId, $qty);
    
            return response()->json($response);
        } else {
            Cart::update($product_rowId, $qty);
    
            return back();
        }
        
    }

    public function clearCart(){
        Cart::destroy();

        Session::flash('status', 'Carrinho limpo');
        return back();
    }
}
