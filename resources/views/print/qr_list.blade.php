@extends('layouts.print')

@section('content')
    <div>
        <div class="columns is-multiline">
            @foreach($devices as $device)
                <div class="column is-4">
                    <div class="visible-print has-text-centered">
                        {{ \SimpleSoftwareIO\QrCode\Facades\QrCode::generate($device->id) }}
                        <p class="is-size-7">S/N: {{ $device->serial_number }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
