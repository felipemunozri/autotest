@extends('layouts.auth')

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
    
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div  class="title is-4">Restaurar Contraseña</div>
                            <p class="mb-3">
                                Ingrese la nueva contraseña del usuario.
                            </p>
                            
                            <div class="field">
                                <label class="label has-text-grey">Email</label>
                                <div class="control">
                                    <input id="email" name="email" class="input is-medium" type="email" value="{{ $email ?? old('email') }}" readonly autocomplete="on" placeholder="Tu correo electrónico" required autofocus>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label has-text-grey">Contraseña</label>
                                <div class="control">
                                    <input id="password" name="password" class="input is-medium" type="password" autocomplete="new-password" placeholder="Contraseña" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label has-text-grey">Confirme Contraseña</label>
                                <div class="control">
                                    <input id="password-confirm" name="password_confirmation" class="input is-medium" type="password" autocomplete="new-password" placeholder="Contraseña" required>
                                </div>
                            </div>

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
                                    <button type="submit" class="button is-block is-info is-fullwidth">Cambiar Contraseña</button>
                                </div>
                            </div>
                            

                            {{-- passwords.reset --}}
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
