<?php
namespace Doomus\Http\Controllers;
use Illuminate\Http\Request;
use Doomus\Product;
use Doomus\Order;
use Doomus\Cupom;
use Doomus\Ticket;
use Session;
use DateTime;
use Gloudemans\Shoppingcart\Facades\Cart;
class AdminController extends Controller
{
    public function index(){ 
        $dataInicial = new DateTime('2019-01-01');
        $dataAtual = new DateTime();
        $diferencaData = $dataInicial->diff($dataAtual);
        $meses = $diferencaData->format('%m');

        $orders = Order::all();

        $qtdPedidosMes_chart[] = ['Mês', 'Quantidade de pedidos', 'Esperado'];
        for($i = 1; $i <= $meses; $i++)
        {
            $somaProdutos = 0;
            $somaPedido = 0;
            foreach($orders as $order)
            {
                foreach($order->product as $product)
                {
                    $somaProdutos += rand(20, 100)*0.2;
                    $somaPedido += rand(50, 150)*0.1;
                }
            }
            $qtdPedidosMes_chart[] = [$i, $somaProdutos, $somaPedido];
        }

        $qtdPedidosStatus_chart[] = ['Pedidos e status', 'Quantidade de pedidos'];
        $qtdPedidosStatus_chart[] = ['Aprovados', 220];
        $qtdPedidosStatus_chart[] = ['Em andamento', 60];
        $qtdPedidosStatus_chart[] = ['Recusados', 120];

        $lucroMensal[] = ['X', 'Lucro'];

        for($i = 1; $i <= $meses; $i++){
            $lucroMensal[] = [$i, rand(1, 10)];
        }

        $arrayqtdMes = $qtdPedidosMes_chart;
        $arrayqtdStatus = $qtdPedidosStatus_chart;

        $dadosChart = [
            'qtdPedidosMes' => json_encode($arrayqtdMes),
            'qtdPedidosStatus' => json_encode($arrayqtdStatus),
            'lucroMensal' => json_encode($lucroMensal),
            'products' => self::products(),
            'orders' => self::orders(),
            'cupons' => self::cupomView(),
            'tickets' => self::ticketView()
        ];
        return view('layouts.admin')->with('dadosChart', $dadosChart);
    }

    public function ticketView () {
        $ticketData = Ticket::all();

        $arrayTickets[] = ['ID', 'Assunto', 'Tipo', 'Mensagem', 'Resposta', 'Data de criação', 'Data de resposta', 'Usuário'];
        
        foreach($ticketData as $data){
            $creation_date = DateTime::createFromFormat('Y-m-d H:i:s', $data->creation_date);
            if (!is_null($data->response_date)) {
                $response_date = DateTime::createFromFormat('Y-m-d H:i:s', $data->response_date);
                $response_date_formatted = $response_date->format('d/m/Y H:i:s');
            } else {
                $response_date_formatted = '';
            }
            $arrayTickets[] = [$data->id, $data->subject, $data->ticket_type->name, $data->message, $data->response, $creation_date->format('d/m/Y H:i:s'), $response_date_formatted, $data->user->email];
        }

        return json_encode($arrayTickets);    
    }

    public static function products(){
        $products = Product::all();
        
        $arrayP[] = ['ID Produto', 'Nome', 'Quantidade', 'Valor', 'Desconto', 'Categoria'];

        foreach($products as $data)
        {
            $arrayP[] = [$data->id, $data->name, $data->qtd_last, $data->price, $data->discount, $data->category->name];
        }

        return json_encode($arrayP);    
    }

    public function ofertaProdutoView($product_id){
        return view('admin.productDesconto')->with('product_id', $product_id);
    }

    public function ofertaCategoriaView(){
        return view('admin.categoryDesconto');
    }

    public function cupomView(){
        $cupons = Cupom::all();
        
        $array[] = ['ID Cupom', 'Nome', 'Fornecido por', 'Desconto'];
        
        foreach($cupons as $data){
            $array[] = [$data->id, $data->name, $data->fornecido_por, "$data->desconto%"];
        
        }
        return json_encode($array);
    }

    public function cupomCreate() {
        return view('admin.create-cupom');
    }

    public function cupomStore(Request $request) {
        $cupom = new Cupom();
        $cupom->name = $request->cupom_name;
        $cupom->fornecido_por = $request->cupom_provider;
        $cupom->desconto = $request->cupom_discount;

        $cupom->save();

        Session::flash('status', 'Cupom criado com sucesso');
        return redirect()->route('admin.index');
    }

    public function cupomDestroy($cupom_id) {
        Cupom::destroy($cupom_id);
        Session::flash('status', 'Cupom deletado com sucesso');
        return redirect()->route('admin.index');
    }

    // Aplicar desconto a um determinado produto
    public function ofertaProduto(Request $data){
        $desconto = $data->desconto * 0.01;
       
        $product = Product::find($data->product_id);
        $product->discount = $desconto;
        $product->save();
        
        Session::flash('status', 'Desconto no produto aplicado com sucesso!');
        return redirect()->route('admin.index');
    }

    // Aplicar desconto a toda uma categoria
    public function ofertaCategoria(Request $request){
        $products = Product::where('category_id', $request->categoria_id)->get();
        
        $desconto = $request->desconto * 0.01;
        
        foreach($products as $product){
            $product->discount = $desconto;
            $product->save();
        }
        Session::flash('status', 'Desconto aplicado na categoria selecionada com sucesso!');
        return redirect()->route('admin.index');
    }

    public function orders(){
        $orders = Order::all();
        $array[] = ['ID Pedido', 'ID Produtos', 'Usuário', 'Status', 'Método de Pagamento'];
        
        foreach($orders as $order){
            $products = "";
            $i = 1;
            foreach($order->product as $item){
                $products .= $i == count($order->product) ? $item->id. "." : $item->id. ", ";
                $i++;
            }
            $array[] = [$order->id, $products, $order->user->id, $order->status->name, $order->payment_method->name];
        }
        
        return json_encode($array);
    }
}