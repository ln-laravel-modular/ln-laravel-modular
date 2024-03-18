@props(['props' => [], 'module' => '', 'name' => '', 'version' => '', 'file' => ''])

@php
@endphp

@empty($props)
  <x-script :module="$module" :name="$name" :version="$version" :file="$file"></x-script>
@else
  @foreach ($props as $prop)
    <x-script :module="$prop['module']" :name="$prop['name'] ?? ''" :version="$prop['version'] ?? ''" :file="$prop['file'] ?? ''"></x-script>
  @endforeach
@endempty
