@props(['props'])
@php
@endphp

@push('styles')
  <link rel="stylesheet" href="/public/vendor/{{ $props }}">
@endpush
