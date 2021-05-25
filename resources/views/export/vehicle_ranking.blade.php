@extends('layouts.print')
@include('export.partials.header')
@section('content')
<div>
    <p class="has-text-weight-bold is-size-6 has-text-centered">Ranking de Vehículos Reportados</p>
</div>
<section class="section" style="padding: 0.5rem 0.5rem">
    <div class="container">
       {{-- @dd($ranking) --}}
        @if(count($ranking) > 0)
            <div class="has-text-centered">
            <table class="table table--font is-fullwidth is-bordered">
                <thead>
                    <tr class="has-td-blue">
                        <th class="has-text-centered">Nº</th>
                        <th class="has-text-centered">Marca</th>
                        <th class="has-text-centered">Modelo</th>
                        <th class="has-text-centered">Recuperado / No Recuperado</th>
                        <th class="has-text-centered">Total de Reportes</th>
                        <th class="has-text-centered">Este Mes</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($ranking as $key => $item)
                    <tr>
                        <td class="has-text-centered">{{ $key + 1 }}</td>
                        <td class="has-text-centered">{{ $item->brand }}</td>
                        <td class="has-text-centered">{{ $item->model }}</td>
                        <td class="has-text-centered">{{ ($item->retrieved + $item->retrieved_with_damage) + $item->total_loss }} / {{ $item->not_retrieved }}</td>
                        <td class="has-text-centered">{{ $item->total }}</td>
                        <td class="has-text-centered">{{ $item->last_month }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        @endif
        <p class="help" style="padding-top: 0.5rem">
            AutoSeguro365 - Generado el {{\Carbon\Carbon::now()->format('d-m-Y')}}
        </p>
    </div>
</section>
@endsection

