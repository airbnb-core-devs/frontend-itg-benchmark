@extends('layouts.new_layout')

@section('title')
Checkout
@endsection

@section('stylesheets')
<style>

</style>
@endsection

@section('content')
<div class="container">
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0"
            aria-valuemax="100">30%</div>
    </div><br>

    <h2>Olá, {{ $user->name }}!</h2><br>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Seu carrinho</span>
                <span class="badge badge-secondary badge-pill">{{ Cart::count() }}</span>
            </h4>
            <ul class="list-group mb-3">
                @foreach (Cart::content() as $item)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">{{ $item->name }}</h6>
                        <small class="text-muted">{{ $item->description }}</small>
                    </div>
                    <span class="text-muted">{{ $item->qty }} x R${{ $item->price }}</span>
                </li>
                @endforeach
                @if(session('cupons') !== null)
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Cupom</h6>
                            <small>TOGURO120</small>
                        </div>
                        <span class="text-success">-R$5</span>
                    </li>
                @endif
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (BRL) s/ frete</span>
                    <strong>R${{Cart::total()}}</strong>
                </li>
            </ul>

            <form class="card p-2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cupom">
                    <div class="input-group-prepend">
                        <button class="mdc-button mdc-button--raised general-button" style="border-radius: 0;">
                            <span class="mdc-button__label">Resgatar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Endereço de entrega</h4>
            <br>
            <form action="/checkout/address/data" method="post" id="addressCheckoutForm">
                @csrf
                <div class="form-group col-lg-6 pl-0">
                    <input type="number" name="cep" id="cep" placeholder="CEP" maxlength="8" class="form-control {{$errors->has('cep') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('cep'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('cep')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" class="form-control {{$errors->has('bairro') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('bairro'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('bairro')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="text" name="address" id="address" placeholder="Rua" class="form-control {{$errors->has('address') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('address'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('address')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <input type="number" name="n" id="n" placeholder="Número" class="form-control {{$errors->has('n') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('n'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('n')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-lg-6 pl-0">
                    <select name="state" id="state" class="form-control {{$errors->has('state') ? 'is-invalid' : ''}}" required>
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
                    <input type="text" name="city" id="city" placeholder="Cidade" class="form-control {{$errors->has('city') ? 'is-invalid' : ''}}" required>
                    @if ($errors->has('state'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('city')}}</strong>
                        </span>
                    @endif
                </div>

                <hr>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address">
                    <label for="same-adress" class="custom-control-label">O endereço de entrega é o mesmo que o de pagamento?</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label for="save-info" class="custom-control-label">Salvar minhas informações para próxima compra</label>
                </div>

                <hr>

                <button class="mdc-button mdc-button--raised general-button w-100">
                    <span class="mdc-button__label">Pronto</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    $(document).ready(function(){
        $.validator.setDefaults({
            highlight : function (element) {
                $(element).addClass('input-invalid');
                $(element.form).find("label[for=" + element.id + "]").addClass('label-invalid mb-0');
            },
            unhighlight: function (element) {
                $(element).removeClass('input-invalid');
                $(element).addClass('input-valid');
                $(element.form).find("label[for=" + element.id + "]").removeClass('label-invalid');
            }
        });
        $('#addressCheckoutForm').validate({
            submitHandler: function(form) {
                alert('oi');
            },
            rules: {
                bairro: {
                    required: true
                },
                address: {
                    required: true
                }
            },
            messages: {
                bairro: {
                    required: 'Por favor, informe um bairro.'
                },
                address: {
                    required: 'Por favor, informe uma rua.'
                }
            }
        });
        //   function search(query = ''){
        //     $.ajax({
        //       url: "{{ route('checkCep') }}",
        //       method: 'GET',
        //       data: {query:query},
        //       success:function(result){
        //         $('#state option').each(function(){
        //             if($(this).val() == result.uf){
        //                 $(this).attr('selected', true);
        //                 $('#state').attr('readonly', "readonly");
        //             }
        //         });
        //         $('#city').val(result.localidade);
        //         $('#city').attr('readonly', "readonly");
        //         if(result.logradouro != '' && result.bairro != ''){
        //             $('#address').val(result.logradouro);
        //             $('#address').attr('readonly', "readonly");
        //             $('#bairro').val(result.bairro);
        //             $('#bairro').attr('readonly', "readonly");
        //         }
        //       }
        //     });
        //   }
        //   $('#cep').keyup(function(){
        //     if($(this).val().length > 8){
        //         $(this).val('');
        //          alert('Seu CEP deve ter apenas 8 números.');
        //     }else{
        //         if($(this).val().length == 8){
        //             var query = $('#cep').val();
                    
        //             search(query);
        //         }
        //     }
        //   });
        
        });
        
</script>
@endsection