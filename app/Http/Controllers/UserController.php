<?php

namespace Doomus\Http\Controllers;

use Doomus\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Doomus\Product;
use Doomus\Http\Controllers\CartController;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Hash;
use willvincent\Rateable\Rating;
use DB;

class UserController extends Controller
{
    public function avaliate(Request $avaliacao){
        $result = DB::table('ratings')->where([['user_id', Auth::id()], ['rateable_id', $avaliacao->product_id]])->first();
        if($result !== null){
            Session::flash('status', 'Você já avaliou esse produto!');
            Session::flash('status-type', 'danger');
            return back();
        }else{   
            $post = Product::find($avaliacao->product_id);
            
            $rating = new Rating;
            $rating->rating = $avaliacao->rate;
            $rating->user_id = Auth::id();
            
            $post->ratings()->save($rating);
            
            Session::flash('status', 'Avaliado com sucesso!');
            return back();
        }
    }

    public function deletarConta (Request $request) {
        $user = Auth::user();
        $user->delete();
        return redirect('/');
    }

    public function deleteAddressSave () {
        $user = Auth::user();
        $user->cep = null;
        $user->bairro = null;
        $user->estado = null;
        $user->cidade = null;
        $user->endereco = null;
        $user->numero = null;
        $user->save();

        Session::flash('status', 'Endereço excluído');
        return back();
    }

    public function getAddressSaved () {
        
        $user = Auth::user();

        $data = [
            'cep' => $user->cep,
            'bairro' => $user->bairro,
            'estado' => $user->estado,
            'cidade' => $user->cidade,
            'endereco' => $user->endereco,
            'numero' => $user->numero,
            'textStatus' => 'success'
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     */
    public function showProfile()
    {
        $user = self::getUser();
        return view('user.profile')->with('user', $user);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = self::getUser();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return back();
    }

    /*
     * Functions to get atributes of user
     * */
    public static function getUser()
    {
        return Auth::guard()->user();
    }

    public static function getOrders()
    {
        return Auth::guard()->user()->order;
    }

    public static function getTickets()
    {
        return Auth::guard()->user()->tickets;
    }

    public static function getCart()
    {
        return Cart::content();
    }
}