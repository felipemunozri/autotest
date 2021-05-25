@component('mail::message')
# Hola, {{ $user }}.

El motivo de este correo es debido a que recibimos una solicitud de cambio de contraseña.


@component('mail::button', ['url' => $url])
Restablecer Contraseña
@endcomponent


Este enlace para restablecer la contraseña caducará en 60 minutos.

Si no ha solicitado el restablecimiento de la contraseña, no es necesario realizar ninguna otra acción.

Gracias, Atte.<br>
{{ config('app.name') }}


@slot('subcopy')
<p>Si tiene problemas para hacer clic en el botón "Restablecer Contraseña", copie y pegue la siguiente URL en su navegador:
<span class="break-all"><a href="{{ $url }}" >{{ $url}}</a></span>
</p>
@endslot


@endcomponent

