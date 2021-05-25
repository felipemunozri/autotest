@component('mail::message')
# Bienvenido, {{ $beneficiary }}.

Ahora, tu vehículo <b>{{ $plate_number }}</b> se encuentra protegido por <b>{{ $tenant }}</b>.

Gracias, Atte.<br>
{{ config('app.name') }}
@endcomponent
