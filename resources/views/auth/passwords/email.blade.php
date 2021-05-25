@extends('layouts.auth')

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@section('content')
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container ">
                <div class="column is-4 is-offset-4">
                    <figure class="login-logo mb-4 has-text-centered">
                        <img src="{{ asset('/images/logo.png') }}">
                    </figure>
                    <div class="box box-login">                        
                        @if (session('retry') || session('status'))
                            <div  class="title is-4">Correo enviado!</div>
                            <p class="mb-3">
                                Se ha enviado el correo exitosamente a <b>{{ session('email') }}</b>.
                            </p>
                        @else
                            <div  class="title is-4">Solicitar cambio de contraseña</div>
                            <p class="mb-3 has-text-justified">
                                Ingresa el correo asociado a tu usuario, se enviarán las instrucciones necesarias para completar la restauración de contraseña.
                            </p>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            
                            @if (session('retry') || session('status'))
                                <input id="email" name="email" type="hidden" value="{{ session('email') }}" autocomplete="on" required>
                                <input id="retry" name="retry" type="hidden" value="1" autocomplete="on" required>
                            @else
                                <div class="field">
                                    <label class="label has-text-grey">Email</label>
                                    <div class="control">
                                        <input id="email" name="email" class="input is-medium" type="email" value="{{ old('email') }}" autocomplete="on" placeholder="Tu correo electrónico" required autofocus>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <article class="message is-danger">
                                    <div class="message-body">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li><small>{{ $error }}</small></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </article>
                            @endif
                            <div class="columns mt-3">
                                <div class="column">
                                    <a href="{{ route('login') }}" class="button is-block is-default is-fullwidth">Salir</a>
                                </div>
                                <div class="column">
                                    <button type="submit" class="button is-block is-info is-fullwidth">
                                        @if (session('retry') || session('status'))
                                            Reenviar
                                        @else
                                            Enviar
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <p class="has-text-grey has-text-centered">
                        <span><a href="http://autoseguro365.com/" style="color:#64B5DB; font-weight: 700;">AutoSeguro365</a> <br> +56993779421 - ayuda@andeslabs.cl</span>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
