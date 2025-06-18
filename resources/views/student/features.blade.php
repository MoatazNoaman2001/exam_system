 @extends('layouts.app')

@section('title', 'Logo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/features.css') }}">
  <div class="background">
     <img src="/images/Web-Onboarding-Features.png" alt="Background" class="background-img" />
  <div class="frame10">
   <div class="frame8">
    <p> تعلم، اختبر، وتقدم بثقة</p>
    <p>.محتوى محدث، تمارين واقعية، وتتبع لتقدمك خطوة بخطوة</p>
   
   <div class=frame4>
    <button>التالى </button>
    <button>السابق</button>
   </div>
<div class="frame3">
  <i class="fas fa-arrow-left"></i>
  <span>تخطي</span>
</div>
   </div>
  </div>
  </div>
@endsection
