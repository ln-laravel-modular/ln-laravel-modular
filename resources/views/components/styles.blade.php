@props(['props' => []])
{{-- @empty($props)
  <x-style :module="$module" :name="$name" :version="$version" :file="$file"></x-style>
@else --}}
@foreach ($props as $prop)
  <x-style :props="$prop"></x-style>
@endforeach
{{-- @endempty --}}
