@extends('layouts.layout')

@section('title', 'Perfil')

@section('stylesheets')
  <link href="{{ asset('/css/styleHome.css') }}" rel="stylesheet"/>
@endsection

@section('content')
<div class="container">
    <form method="post" action="/perfil/update" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="InputEmail">Email</label>
                    <input type="email" class="form-control" id="InputEmail" name="email" aria-describedby="emailHelp" placeholder="{{ $user->email }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="InputName">Nome</label>
                    <input type="text" name="name" class="form-control" id="InputName" placeholder="{{ $user->name }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="InputPass">Senha</label>
                    <input type="password" class="form-control" name="password" id="InputPass" placeholder="Insira sua senha..">
                </div>
            </div>
        <button class="btn btn-success" type="submit">Atualizar</button>
    </form>
    <form action="{{ route('deletarConta') }}" method="post">
        @csrf
        <button class="btn btn-danger float-right" type="submit">Deletar conta</button>
    </form>
</div>
<div style="height:130px"></div>
@endsection