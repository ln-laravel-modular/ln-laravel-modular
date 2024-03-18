@props(['module' => '', 'name' => '', 'version' => '', 'file' => ''])
@php
  // $module=empty($module)?'':'/modules/'
@endphp

@empty($name)
  <link rel="stylesheet" href="{{ $file }}">
@else
  <link rel="stylesheet" href="/public/vendor/{{ $name }}/{{ $version }}/{{ $file }}">
@endempty
