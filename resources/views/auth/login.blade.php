@extends('layouts.auth')
@section('content')
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container ">
                <div class="column is-4 is-offset-4">
                    <figure class="login-logo mb-4 has-text-centered">
                        <img src="{{ asset('/images/logo.png') }}">
                    </figure>
                    <!-- <h3 class="title has-text-grey">AutoSeguro365</h3>
                    <p class="subtitle has-text-grey">Seguridad Ciudadana</p> -->
                    <div class="box box-login">
                        <form method="POST" action="/login">
                            @csrf
                            <div class="field">
                                <label class="label has-text-grey">Email</label>
                                <div class="control">
                                    <input id="email" name="email" class="input is-medium" type="email" autocomplete="on" placeholder="Tu correo electrónico" required autofocus>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label has-text-grey">Contraseña</label>
                                <div class="control">
                                    <input id="password" name="password" class="input is-medium" type="password" autocomplete="on" placeholder="Contraseña" required>
                                </div>
                            </div>
                            {{--<div class="field">
                                <label class="checkbox">
                                    <input type="checkbox">
                                    Remember me
                                </label>
                            </div>--}}
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
                            
                            <button type="submit" class="button is-block is-info is-fullwidth">Ingresar</button>
                            <div class="colums">
                                <div class="column has-text-centered">
                                    <a href="{{ route('password.request') }}" class="has-text-weight-bold is-size-7 mt-2">¿Olvidaste tu contraseña?</a>
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
