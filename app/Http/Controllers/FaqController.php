<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQController extends Controller
{
    // بيانات الأسئلة (يمكن لاحقًا نقلها لقاعدة البيانات)
    private $faqs = [
        [
            'category' => 'account',
            'question' => 'How do I reset my password?',
            'answer' => 'To reset your password, go to the login page and click on "Forgot Password".'
        ],
        [
            'category' => 'account',
            'question' => 'Can I change my email address?',
            'answer' => 'Yes, you can change your email address in the account settings section.'
        ],
        [
            'category' => 'billing',
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major credit cards (Visa, MasterCard, American Express) as well as PayPal.'
        ],
        [
            'category' => 'billing',
            'question' => 'How do I cancel my subscription?',
            'answer' => 'You can cancel your subscription at any time by going to the "Billing" section in your account settings.'
        ],
        [
            'category' => 'features',
            'question' => 'How do I use the advanced analytics feature?',
            'answer' => 'Our advanced analytics can be accessed from the dashboard. Click on "Analytics" in the main menu.'
        ],
        [
            'category' => 'troubleshooting',
            'question' => 'The page isn\'t loading correctly. What should I do?',
            'answer' => 'Try refreshing the page, clear your browser cache and cookies, or try using a different browser.'
        ]
    ];

    private $categories = [
        'all' => 'All Questions',
        'account' => 'Account',
        'billing' => 'Billing',
        'features' => 'Features',
        'troubleshooting' => 'Troubleshooting'
    ];

    /**
     * عرض صفحة الأسئلة الشائعة
     */
    public function index()
    {
        return view('student.FAQ', [
            'faqs' => $this->faqs,
            'categories' => $this->categories
        ]);
    }

    /**
     * بحث الأسئلة الشائعة (AJAX)
     */
    public function search(Request $request)
    {
        $query = strtolower($request->input('search'));

        $filtered = array_filter($this->faqs, function ($faq) use ($query) {
            return str_contains(strtolower($faq['question']), $query) ||
                   str_contains(strtolower($faq['answer']), $query);
        });

        return view('student.partials.faq_list', ['faqs' => $filtered])->render();
    }
}
