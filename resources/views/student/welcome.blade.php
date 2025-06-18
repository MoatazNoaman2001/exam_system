@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

  <div class="web-onboarding-welcome">
        <img class="line-3" src="/images/Line3.png" alt="Line 3" />
        <img class="line-1" src="/images/Line1.png" alt="Line 1" />
        <img class="line-4" src="/images/Line4.png" alt="Line 4" />
        <img class="line-2" src="/images/Line2.png" alt="Line 2" />
       <div class="homeindicator">
    <div class="home-indicator"></div>
  </div>
  <div class="frame-3">
    <div class="arrow-left">
      <img
        class="vuesax-outline-arrow-left"
        src="/images/arrow-left.png"
      />
    </div>
    <div class="div">تخطي</div>
  </div>
  <div class="frame-8">
    <div class="pmp-app">
      <span>
        <span class="pmp-app-span">
          أهلاً بك في
          <br />
        </span>
        <span class="pmp-app-span2">PMP App</span>
      </span>
    </div>
    <div class="div2">
      رفيقك الذكي
      <br />
      في طريقك للاحتراف والاعتماد المهني
    </div>
  </div>
  <div class="button">
    <div class="text">التالي</div>
  </div>
  <div class="frame-9">
    <img
      class="svax-blob-2-1080-x-1080-2-1"
      src="/images/Frame 9.png"
    />
  </div>
</div>

@endsection

