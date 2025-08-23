<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of domains
     */
    public function index()
    {
        $domains = Domain::withCount('slides')->with('certificate')->latest()->paginate(20);
        return view('admin.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new domain
     */
    public function create()
    {
        $certificates = Certificate::active()->ordered()->get();
        return view('admin.domains.create', compact('certificates'));
    }

    /**
     * Store a newly created domain
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'required|string|max:200',
            'description_ar' => 'required|string|max:200',
            'certificate_id' => 'required|exists:certificates,id',
        ]);

        try {
            DB::beginTransaction();

            $domain = Domain::create($request->only(['text', 'description', 'description_ar', 'certificate_id']));

            // Update certificate's updated_at timestamp to indicate changes
            $domain->certificate()->touch();

            DB::commit();

            return redirect()->route('admin.domains')->with('success', 'Domain created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating domain: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified domain
     */
    public function show(Domain $domain)
    {
        $domain->load(['certificate', 'slides']);
        return view('admin.domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified domain
     */
    public function edit(Domain $domain)
    {
        $certificates = Certificate::active()->ordered()->get();
        return view('admin.domains.edit', compact('domain', 'certificates'));
    }

    /**
     * Update the specified domain
     */
    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'required|string|max:200',
            'description_ar' => 'required|string|max:200',
            'certificate_id' => 'required|exists:certificates,id',
        ]);

        try {
            DB::beginTransaction();

            $oldCertificateId = $domain->certificate_id;
            
            $domain->update($request->only(['text', 'description', 'description_ar', 'certificate_id']));

            // Update the new certificate's timestamp
            $domain->certificate()->touch();

            // If certificate changed, also update the old certificate's timestamp
            if ($oldCertificateId && $oldCertificateId !== $domain->certificate_id) {
                Certificate::find($oldCertificateId)?->touch();
            }

            DB::commit();

            return redirect()->route('admin.domains.index')->with('success', 'Domain updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating domain: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified domain
     */
    public function destroy(Domain $domain)
    {
        try {
            DB::beginTransaction();

            $certificate = $domain->certificate;
            
            $domain->delete();

            // Update certificate's timestamp to reflect the change
            $certificate?->touch();

            DB::commit();

            return redirect()->route('admin.domains')->with('success', 'Domain deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.domains')->with('error', 'Error deleting domain: ' . $e->getMessage());
        }
    }

    /**
     * Get domains by certificate (AJAX)
     */
    public function getByCertificate(Certificate $certificate)
    {
        $domains = $certificate->domains()->orderBy('text')->get();
        return response()->json($domains);
    }
}