@extends('layouts.layout')

@section('title', 'Confirmar endereço de entrega')

@section('content')
<div class="container">
    <div class="progress mt-4">
        <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0"
            aria-valuemax="100">30%</div>
    </div>
    <div class="mt-2 alert alert-danger d-none" id="alertError"></div>
    <div class="mt-2 alert alert-success d-none" id="alertSuccess"></div>

    <h2>Olá, {{ $user->name }}!</h2><br>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <div class="p-3" style="background-color: #f7f5f3">
                <h4 class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Seu carrinho</span>
                    <span class="badge badge-secondary">{{ Cart::count() }}</span>
                </h4>
                <table class="table table-borderless">
                    <tbody>
                        @foreach(Cart::content() as $item)
                        <tr class="" style="background-color:white;">
                            <th>{{$item->name}}</th>
                            <td class="text-muted">{{$item->qty}} x R$
                                @php
                                    echo number_format($item->price, 2, ',', '');
                                @endphp
                            </td>
                        </tr>
                        @endforeach  
                        <tr class="border-top d-none text-success cupomTr">
                            <th>Cupom <small id="cupomText"></small></th>
                            <td class="text-success text-right">     
                                <div id="descontoPorcentagem"></div>
                                <span id="descontoValor"></span>
                            </td>
                        </tr>
                        <tr id="dadosFrete" class="d-none">
                            <th id="prazoFrete"></th>
                            <td id="valorFrete" class="text-right"></td>
                        </tr>
                        
                        <tr class="border-top" style="border-top-color: (0, 0, 0, 0.1)">
                            <th id="totalOpcao"></th>
                            <td class="font-weight-bold text-right" id="totalCart" data-total-carrinho="{{Cart::total()}}"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cupom" aria-describedby="botaoCupom"
                        id="cupomValue">
                    <div class="input-group-append">
                        <button class="mdc-button mdc-button--raised general-button" id="botaoCupom"
                            style="border-radius: 0;">
                            <span class="mdc-button__label" id="botaoCupomLabel">Resgatar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 order-md-1">
            <div class="d-flex d-inline mb-4">
                <h4>Endereço de entrega</h4>
                @if (Auth::user()->endereco !== null)
                    &nbsp;&nbsp;&nbsp;&nbsp;<button id="useAddressSaved" class="mdc-button mdc-button--raised general-button">
                        <span class="mdc-button__label">Usar endereço salvo</span>
                    </button>
                @endif
            </div>
            <form action="{{ route('address-data') }}" method="post" id="addressCheckoutForm">
                @csrf
                <div class="form-group col-lg-6 pl-0">
                    <input type="text" name="address" id="address" placeholder="Rua"
                        class="form-control {{$errors->has('address') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$errors->first('address')}}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="number" name="n" id="n" placeholder="Número"
                        class="form-control {{$errors->has('n') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('n'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$errors->first('n')}}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro"
                        class="form-control {{$errors->has('bairro') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('bairro'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$errors->first('bairro')}}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0 cep-form-group">
                    <input type="number" name="cep" id="cep" placeholder="CEP" maxlength="8" minlength="8"
                        class="form-control {{$errors->has('cep') ? 'is-invalid' : ''}}" @if(session('cep') !== null)value="{{session('cep')}}"@endif required>
                    @if ($errors->has('cep'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('cep')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <select name="state" id="state" class="form-control {{$errors->has('state') ? 'is-invalid' : ''}}"
                        required>
                        <option selected value="">Estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espirito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                    @if ($errors->has('state'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('state')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="text" name="city" id="city" placeholder="Cidade"
                        class="form-control {{$errors->has('city') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('state'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('city')}}</strong>
                        </span>
                    @endif
                </div>

                <hr>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info" name="saveInfo">
                    <label for="save-info" class="custom-control-label">Salvar minhas informações para próxima
                        compra</label>
                </div>

                <hr>

                <button type="submit"
                    class="mdc-button mdc-button--raised general-button w-100 submitButtonAddressForm">
                    <span class="mdc-button__label">Continuar</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/customJs/addressCheckout.js')}}"></script>
@endsection