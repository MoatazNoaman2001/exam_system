<?php
namespace App\Http\Controllers;

use App\Models\Faq;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index(): View
    {
        $faqs = Faq::active()->ordered()->get();
        return view('faq', compact('faqs'));
    }

    public function adminIndex(): View
    {
        $faqs = Faq::ordered()->get();
        return view('admin.faq.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faq.create');
    }

    public function store(StoreFaqRequest $request): RedirectResponse
    {
        $faq = new Faq([
            'order' => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true)
        ]);

        // Set multilingual content
        $faq->setQuestionForLocale('en', $request->question_en);
        $faq->setAnswerForLocale('en', $request->answer_en);
        
        if ($request->question_ar) {
            $faq->setQuestionForLocale('ar', $request->question_ar);
        }
        
        if ($request->answer_ar) {
            $faq->setAnswerForLocale('ar', $request->answer_ar);
        }

        $faq->save();

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ created successfully!');
    }

    public function show(Faq $faq): View
    {
        return view('admin.faq.show', compact('faq'));
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->order = $request->order ?? 0;
        $faq->is_active = $request->boolean('is_active', true);

        // Update multilingual content
        $faq->setQuestionForLocale('en', $request->question_en);
        $faq->setAnswerForLocale('en', $request->answer_en);
        
        if ($request->question_ar) {
            $faq->setQuestionForLocale('ar', $request->question_ar);
        }
        
        if ($request->answer_ar) {
            $faq->setAnswerForLocale('ar', $request->answer_ar);
        }

        $faq->save();

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ updated successfully!');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();
        
        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ deleted successfully!');
    }

    // Toggle active status
    public function toggleStatus(Faq $faq): RedirectResponse
    {
        $faq->update(['is_active' => !$faq->is_active]);
        
        $status = $faq->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "FAQ {$status} successfully!");
    }

    // Reorder FAQs
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'faqs' => 'required|array',
            'faqs.*.id' => 'required|exists:faqs,id',
            'faqs.*.order' => 'required|integer|min:0'
        ]);

        foreach ($request->faqs as $faqData) {
            Faq::where('id', $faqData['id'])->update(['order' => $faqData['order']]);
        }

        return redirect()->back()
            ->with('success', 'FAQ order updated successfully!');
    }
}