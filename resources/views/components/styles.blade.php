@props(['props' => [], 'module' => '', 'name' => '', 'version' => '', 'file' => ''])

@empty($props)
  <x-style :module="$module" :name="$name" :version="$version" :file="$file"></x-style>
@else
  @foreach ($props as $prop)
    <x-style :module="$prop['module'] ?? ''" :name="$prop['name'] ?? ''" :version="$prop['version'] ?? ''" :file="$prop['file'] ?? ''"></x-style>
  @endforeach
@endempty
