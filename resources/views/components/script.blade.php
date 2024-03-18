@props(['module' => '', 'name' => '', 'version' => '', 'file' => ''])
@php
  // $module=empty($module)?'':'/modules/'
@endphp

@empty($name)
  <script type="text/javascript" src="{{ $file }}"></script>
@else
  <script type="text/javascript" src="/public/vendor/{{ $name }}/{{ $version }}/{{ $file }}">
  </script>
@endempty
