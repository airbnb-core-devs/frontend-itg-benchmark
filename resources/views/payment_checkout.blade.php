@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>Formas de pagamento</h3><br>
                <a href="/sucesso" class="btn btn-success">Pagar com PayPal</a><br><br>
                <h6>Cartão de crédito</h6><br>
                <h6>Boleto bancário</h6>
                <a href="#">Gerar boleto</a>
            </div>
            <div class="col-md-6">
                <h3>Suas informações</h3><br>
                <h6>Entrega</h6>
                @foreach(session('userData') as $row)
                    {{$row}} &nbsp;
                @endforeach
                <br><br>
                <h6>Pedido</h6>
                @foreach(Cart::content() as $row)
                    {{ $row->name }} &nbsp; {{ $row->qty }} x {{ $row->price }} <br>
                @endforeach
            </div>
        </div>
    </div>
@endsection