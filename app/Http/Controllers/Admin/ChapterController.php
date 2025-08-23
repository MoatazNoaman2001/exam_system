<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of chapters
     */
    public function index()
    {
        $chapters = Chapter::withCount('slides')->with('certificate')->latest()->paginate(20);
        return view('admin.chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new chapter
     */
    public function create()
    {
        $certificates = Certificate::active()->ordered()->get();
        return view('admin.chapters.create', compact('certificates'));
    }

    /**
     * Store a newly created chapter
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'certificate_id' => 'required|exists:certificates,id',
        ]);

        DB::beginTransaction();

        $chapter = Chapter::create($request->only(['text', 'certificate_id']));
        // Update certificate's updated_at timestamp to indicate changes
        $chapter->certificate()->touch();
        DB::commit();
        return redirect()->route('admin.chapters')->with('success', 'Chapter created successfully.');
    }

    /**
     * Display the specified chapter
     */
    public function show(Chapter $chapter)
    {
        $chapter->load(['certificate', 'slides']);
        return view('admin.chapters.show', compact('chapter'));
    }

    /**
     * Show the form for editing the specified chapter
     */
    public function edit(Chapter $chapter)
    {
        $certificates = Certificate::active()->ordered()->get();
        return view('admin.chapters.edit', compact('chapter', 'certificates'));
    }

    /**
     * Update the specified chapter
     */
    public function update(Request $request, Chapter $chapter)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'certificate_id' => 'required|exists:certificates,id',
        ]);

        try {
            DB::beginTransaction();

            $oldCertificateId = $chapter->certificate_id;
            
            $chapter->update($request->only(['text', 'certificate_id']));

            // Update the new certificate's timestamp
            $chapter->certificate()->touch();

            // If certificate changed, also update the old certificate's timestamp
            if ($oldCertificateId && $oldCertificateId !== $chapter->certificate_id) {
                Certificate::find($oldCertificateId)?->touch();
            }

            DB::commit();

            return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating chapter: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified chapter
     */
    public function destroy(Chapter $chapter)
    {
        try {
            DB::beginTransaction();

            $certificate = $chapter->certificate;
            
            $chapter->delete();

            // Update certificate's timestamp to reflect the change
            $certificate?->touch();

            DB::commit();

            return redirect()->route('admin.chapters')->with('success', 'Chapter deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.chapters')->with('error', 'Error deleting chapter: ' . $e->getMessage());
        }
    }

    /**
     * Get chapters by certificate (AJAX)
     */
    public function getByCertificate(Certificate $certificate)
    {
        $chapters = $certificate->chapters()->orderBy('text')->get();
        return response()->json($chapters);
    }
}