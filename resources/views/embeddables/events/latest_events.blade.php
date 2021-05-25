@extends('layouts.embeddable')

@push('scripts')
    <script>
        window.__DATA__ = @json($data);
    </script>
    <script src="{{ mix('js/embeddables/events/latestEvents.js') }}" defer></script>
@endpush