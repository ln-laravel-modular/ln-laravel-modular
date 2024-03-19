{{-- @props(['module' => '', 'name' => '', 'version' => '', 'file' => '']) --}}
@props(['props' => []])

{{-- @empty($name)
  <script type="text/javascript" src="{{ $file }}"></script>
@else
  <script type="text/javascript" src="/public/vendor/{{ $name }}/{{ $version }}/{{ $file }}">
  </script>
@endempty --}}
@php
  //   ['module' => '', 'name' => '', 'version' => 'latest', 'file' => ''];
  //   ['module', 'name', 'version', 'file'];
  //   ['name', 'version', 'file'];
  //   ['name', 'file'];
  //   ['file'];
  // $module=empty($module)?'':'/modules/'
  $module = Arr::get($props, 'module');

  $name = Arr::get($props, 'name');

  $version = Arr::get($props, 'version');

  $file = Arr::get($props, 'file');

  if (empty($file)) {
      if (in_array(sizeof($props), [1, 2, 3, 4])) {
          $file = $props[sizeof($props) - 1];
      }
      if (in_array(sizeof($props), [3, 4])) {
          $version = $props[sizeof($props) - 2];
          $name = $props[sizeof($props) - 3];
      }
      if (in_array(sizeof($props), [2])) {
          $name = $props[sizeof($props) - 2];
          $versions = app('files')->directories('public/vendor/' . $name . '/');
          $version = basename($versions[sizeof($versions) - 1]);
          //   var_dump($version);
          //   var_dump(app('files')->directories('public/vendor/' . $name . '/'));
      }
      if (in_array(sizeof($props), [4])) {
          $module = $props[0];
      }
  }

  $path = '';
  if (!empty($module)) {
      $path .= '/modules/' . $module;
  }
  if (!empty($name)) {
      $path .= '/public/vendor/' . $name;
  }
  if (!empty($version)) {
      $path .= '/' . $version;
  }
  if (!empty($file)) {
      $path .= Str::startsWith($file, '/') ? $file : '/' . $file;
  }

  if (!Str::endsWith($path, '.js')) {
      $path .= '.js';
  }
@endphp

@empty($path)
@else
  <script type="text/javascript" src="{{ $path }}"></script>
@endempty
