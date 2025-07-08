
@extends('layouts.app')

@section('title', 'splash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/splash.css') }}">

<div class="web-splash">
  <div class="web-splash__logo">Logo</div>
</div>

 <script>
        setTimeout(function () {
            window.location.href = "{{ route('feature') }}";
        }, 1000);
    </script>
@endsection