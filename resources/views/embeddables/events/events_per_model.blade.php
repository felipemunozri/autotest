@extends('layouts.embeddable')

@push('scripts')
    <script>
        window.__DATA__ = @json($data);
    </script>
    <script src="{{ mix('js/embeddables/events/eventsPerModel.js') }}" defer></script>
@endpush