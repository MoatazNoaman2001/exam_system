@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/FAQ.css') }}">


<div class="container py-5">
    <!-- Header Section -->
    <div class="faq-header text-center">
        <h1 class="fw-bold mb-3">الأسئلة الشائعة</h1>
        <p class="lead mb-4">كل ما تحتاج معرفته في مكان واحد</p>
        
        <div class="search-container">
            <div class="input-group">
                <input type="text" class="form-control search-input" id="faqSearch" placeholder="ابحث في الأسئلة الشائعة...">
                <button class="btn btn-light text-primary" type="button" style="border-radius: 0 50px 50px 0">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- FAQ List -->
    <div id="faqList">
        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(1)">
                <span>1. كيف يمكنني التواصل معكم؟</span>
                <i class="bi bi-chevron-down" id="icon-1"></i>
            </div>
            <div class="faq-answer" id="answer-1">
                <ul class="answer-steps">
                    <li>انتقل إلى صفحة الإعدادات</li>
                    <li>اختر "تواصل معنا" من القائمة</li>
                    <li>اختر طريقة التواصل</li>
                </ul>
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(2)">
                <span>2. هل يمكن تغيير بياناتي الشخصية؟</span>
                <i class="bi bi-chevron-down" id="icon-2"></i>
            </div>
            <div class="faq-answer" id="answer-2">
                <ul class="answer-steps">
                    <li>سجل دخولك إلى حسابك الشخصي</li>
                    <li>انتقل إلى قسم "الملف الشخصى"</li>
                    <li>قم بإجراء التعديلات المطلوبة</li>
                    <li>احفظ التغييرات</li>
                </ul>
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(3)">
                <span>3. كيف يمكن تغيير كلمة مرور الحساب؟</span>
                <i class="bi bi-chevron-down" id="icon-3"></i>
            </div>
            <div class="faq-answer" id="answer-3">
                <ul class="answer-steps">
                    <li>اذهب إلى صفحة تسجيل الدخول</li>
                    <li>اضغط على "نسيت كلمة المرور"</li>
                    <li>أدخل بريدك الإلكتروني المسجل</li>
                    <li>اتبع الرابط الذي سنرسله إليك</li>
                    <li>قم بتعيين كلمة مرور جديدة</li>
                </ul>
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(4)">
                <span>4. كيف يمكن تقييم الخدمة؟</span>
                <i class="bi bi-chevron-down" id="icon-4"></i>
            </div>
            <div class="faq-answer" id="answer-4">
                <ul class="answer-steps">
                    <li>انتقل إلى صفحة الإعدادات</li>
                    <li>  انتقل الى قسم " التعليق" </li>
                    <li>اختر تقييمك</li>
                    <li>اكتب ملاحظاتك إذا رغبت</li>
                    <li>اضغط "إرسال التقييم"</li>
                </ul>
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(5)">
                <span>5. كيف يمكن زيادة نقاطي؟</span>
                <i class="bi bi-chevron-down" id="icon-5"></i>
            </div>
            <div class="faq-answer" id="answer-5">
                <ul class="answer-steps">
                    <li>اذهب إلى صفحة "الإنجازات"</li>
                    <li>اضغط على "طرق الحصول على نقاط"</li>
                    <li>ستجد قائمة بجميع الطرق المتاحة</li>
                    <li>قم بإكمال المهام المطلوبة</li>
                    <li>ستضاف النقاط تلقائياً لحسابك</li>
                </ul>
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-question" onclick="toggleFAQ(6)">
                <span>6. متى يمكنني استلام الشهادة؟</span>
                <i class="bi bi-chevron-down" id="icon-6"></i>
            </div>
            <div class="faq-answer" id="answer-6">
                <ul class="answer-steps">
                    <li>بعد الانتهاء من جميع الاختبارات المطلوبة</li>
                    <li>يمكنك تحميلها من قسم "الشهادات" في حسابك</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- No Results -->
    <div id="noResults" class="no-result-card d-none">
        <i class="bi bi-search display-4 text-primary mb-3"></i>
        <h4 class="text-primary mb-2">لا نستطيع إيجاد ما تبحث عنه</h4>
        <p class="text-muted mb-4">جرب كلمات بحث مختلفة أو تواصل معنا مباشرة</p>
        <button id="resetSearch" class="btn btn-primary px-4">إعادة البحث</button>
    </div>

    <!-- Help Section -->
    <div class="help-section text-center">
        <h3 class="text-primary mb-3">هل لديك سؤال آخر؟</h3>
        <p class="mb-4">فريق الدعم لدينا جاهز لمساعدتك على مدار الساعة</p>
        <a href="{{ route('contact.us') }}" class="btn btn-primary mx-2"><i class="bi bi-headset me-2"></i> تواصل معنا </a>
    </div>
</div>

<script>
    function toggleFAQ(index) {
        const icon = document.getElementById(`icon-${index}`);
        const answer = document.getElementById(`answer-${index}`);
        
        if (answer.classList.contains('show')) {
            answer.classList.remove('show');
            icon.classList.remove('rotate-icon');
        } else {
            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.faq-card i').forEach(el => el.classList.remove('rotate-icon'));
            
            // Open selected FAQ
            answer.classList.add('show');
            icon.classList.add('rotate-icon');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const faqCards = document.querySelectorAll('.faq-card');
        const noResults = document.getElementById('noResults');
        const resetBtn = document.getElementById('resetSearch');

        // Search functionality
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            let anyVisible = false;

            faqCards.forEach(card => {
                const question = card.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = card.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    card.style.display = 'block';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.classList.toggle('d-none', anyVisible);
        });

        // Reset search
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            faqCards.forEach(card => card.style.display = 'block');
            noResults.classList.add('d-none');
        });
    });
</script>
@endsection