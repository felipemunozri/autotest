@extends('layouts.print')
@include('export.partials.header')
@section('content')
<div>
    <p class="has-text-weight-bold is-size-6 has-text-centered">{{ $title }}</p>
</div>
<section class="section" style="padding: 0.5rem 0.5rem">
    <div class="container" style="width: 700px">
       {{-- @dd($ranking) --}}
       <iframe
        src="{{$url}}"
        frameborder="0"
        width="100%"
        allowtransparency
        onload="resizeIframe(this)"
        ></iframe>
        <p class="help" style="padding-top: 0.5rem">
            AutoSeguro365 - Generado el {{\Carbon\Carbon::now()->format('d-m-Y')}}
        </p>
    </div>
</section>

@endsection



@push('scripts')
    <script>
        function resizeIframe(iframe) {
            iframe.height = (iframe.contentWindow.document.body.scrollHeight+20 )+ "px";
        }
    </script> 
@endpush


