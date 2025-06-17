



 @extends('layouts.app')

@section('title', 'Logo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/logo.css') }}">
  <div class="background">
     <img src="/images/70e80d344c1c52645d9005d789c5a192bb7f2c85.png" alt="Background" class="background-img" />
    <div class="frame10 ">
    <div class="text-center  frame8">
        <h1 class="logo-text mb-2">Logo</h1>
        <p class="ready">جاهز للانطلاق؟</p>
        <p class="start">.ابدأ اﻵن رحلتك نحو الاحترافية</p>
        <button class="use btn btn-primary mt-3">بدء الاستخدام</button>
    </div>
    </div>
  </div>
@endsection
