<?php

   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use App\Models\ContactUs;

   class ContactUsController extends Controller
   {
       public function index(Request $request)
       {
           $user = Auth::user();
           // If admin, show all feedbacks
           if ($user && $user->is_admin) {
               $feedbacks = ContactUs::latest()->paginate(20);
               return view('admin.contactus.index', compact('feedbacks'));
           }
           return view('student.ContactUs', compact('user'));
       }

       public function store(Request $request)
       {
           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|email|max:255',
               'subject' => 'required|string|max:255',
               'message' => 'required|string',
           ]);
           ContactUs::create($validated);
           return redirect()->back()->with('success', __('lang.contact_success'));
       }
   }