<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactReply;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['adminIndex', 'show', 'reply', 'markAsRead', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::active()->ordered()->take(8)->get();
        return view('contact', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        try {
            Mail::to(config('mail.admin_email', 'admin@sprintskills.com'))
                ->send(new ContactFormSubmitted($contact));
        } catch (\Exception $e) {
            \Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', __('contact.message_sent_success'));
    }

    public function adminIndex(Request $request): View
    {
        $query = Contact::with('repliedBy')->recent();

        // Filter by status
        if ($request->status && in_array($request->status, ['unread', 'read', 'replied'])) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::unread()->count(),
            'read' => Contact::read()->count(),
            'replied' => Contact::replied()->count(),
        ];

        return view('admin.contact.index', compact('contacts', 'stats'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        if ($contact->isNew()) {
            $contact->markAsRead();
        }

        return view('admin.contact.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact): RedirectResponse
    {
        $request->validate([
            'reply' => 'required|string|max:2000'
        ]);

        $contact->markAsReplied($request->reply, auth()->id());

        // Send reply email to user
        try {
            Mail::to($contact->email)
                ->send(new ContactReply($contact, $request->reply));
        } catch (\Exception $e) {
            \Log::error('Failed to send contact reply email: ' . $e->getMessage());
            return redirect()->back()
                ->with('warning', 'Reply saved but email failed to send.');
        }

        return redirect()->back()
            ->with('success', 'Reply sent successfully!');
    }

    // Mark contact as read
    public function markAsRead(Contact $contact): RedirectResponse
    {
        $contact->markAsRead();
        
        return redirect()->back()
            ->with('success', 'Contact marked as read.');
    }

    // Delete contact
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        
        return redirect()->route('admin.contact.index')
            ->with('success', 'Contact deleted successfully!');
    }

    // Bulk actions
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:mark_read,delete',
            'contacts' => 'required|array',
            'contacts.*' => 'exists:contacts,id'
        ]);

        $contacts = Contact::whereIn('id', $request->contacts);

        switch ($request->action) {
            case 'mark_read':
                $contacts->update(['status' => 'read']);
                $message = 'Selected contacts marked as read.';
                break;
            
            case 'delete':
                $contacts->delete();
                $message = 'Selected contacts deleted.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    // Export contacts
    public function export(Request $request)
    {
        $query = Contact::with('repliedBy');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $contacts = $query->get();

        $filename = 'contacts_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Subject', 'Message', 
                'Status', 'Created At', 'Replied By', 'Replied At'
            ]);

            // CSV data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->subject,
                    $contact->message,
                    $contact->status,
                    $contact->created_at->format('Y-m-d H:i:s'),
                    $contact->repliedBy?->name ?? '',
                    $contact->replied_at?->format('Y-m-d H:i:s') ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

}
