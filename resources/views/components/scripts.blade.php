@props([
    'props' => [],
    'name' => '',
    'file' => '',
])
@php
@endphp

@push('scripts')
  <script src="/public/vendor/{{ $props }}"></script>
@endpush
