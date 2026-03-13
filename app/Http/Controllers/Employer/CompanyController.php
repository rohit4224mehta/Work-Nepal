<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Show step 1 - Basic Information
     */
    public function create()
    {
        // Clear any existing session data when starting fresh
        if (!request()->has('resume')) {
            session()->forget('company_data');
        }
        
        return view('employer.company.create');
    }

    /**
     * Store step 1 data in session
     */
    public function storeStep1(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255|unique:companies,name',
            'industry' => 'required|string|max:100',
            'company_size' => 'nullable|string|max:50',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'website' => 'nullable|url|max:255',
        ]);

        // Store in session with proper key
        $companyData = session('company_data', []);
        $companyData['name'] = $request->company_name;
        $companyData['industry'] = $request->industry;
        $companyData['company_size'] = $request->company_size;
        $companyData['founded_year'] = $request->founded_year;
        $companyData['website'] = $request->website;
        
        session(['company_data' => $companyData]);

        // Debug: Check if session is set
        \Log::info('Step 1 completed', session('company_data'));

        return redirect()->route('employer.company.details')
            ->with('success', 'Step 1 completed! Please provide more details.');
    }

    /**
     * Show step 2 - Company Details
     */
    public function details()
    {
        $companyData = session('company_data');
        
        // Debug: Log session data
        \Log::info('Step 2 access - Session data:', $companyData ?? []);
        
        // Check if step 1 is completed
        if (empty($companyData['name'])) {
            return redirect()->route('employer.company.create')
                ->with('error', 'Please complete step 1 first. No company data found.');
        }

        return view('employer.company.details');
    }

    /**
     * Store step 2 data in session
     */
    public function storeStep2(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'headquarters' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'required|string|min:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ]);

        // Store in session
        session(['company_data' => array_merge(session('company_data', []), $request->all())]);

        return redirect()->route('employer.company.branding')
            ->with('success', 'Step 2 completed!');
    }

    /**
     * Show step 3 - Branding
     */
    public function branding()
    {
        // Check if step 2 is completed
        if (!session('company_data.location')) {
            return redirect()->route('employer.company.details')
                ->with('error', 'Please complete step 2 first');
        }

        return view('employer.company.branding');
    }

    /**
     * Store step 3 data (file uploads)
     */
    public function storeStep3(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'culture_image_1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'culture_image_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'culture_image_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_link' => 'nullable|url|max:255',
        ]);

        $uploadedFiles = [];
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company-logos', 'public');
            $uploadedFiles['logo'] = $path;
        }

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('company-covers', 'public');
            $uploadedFiles['cover_image'] = $path;
        }

        // Handle culture images
        $cultureCount = 0;
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("culture_image_$i")) {
                $path = $request->file("culture_image_$i")->store('company-culture', 'public');
                $uploadedFiles["culture_image_$i"] = $path;
                $cultureCount++;
            }
        }
        $uploadedFiles['culture_photos_count'] = $cultureCount;

        // Store in session
        session(['company_data' => array_merge(session('company_data', []), $uploadedFiles)]);

        return redirect()->route('employer.company.review')
            ->with('success', 'Step 3 completed!');
    }

    /**
     * Show step 4 - Review
     */
    public function review()
    {
        // Check if step 3 is completed
        if (!session('company_data.logo')) {
            return redirect()->route('employer.company.branding')
                ->with('error', 'Please complete step 3 first');
        }

        return view('employer.company.review');
    }

    /**
     * Final submission - Create company in database
     */
    public function storeFinal(Request $request)
    {
        $data = session('company_data');
        
        if (!$data) {
            return redirect()->route('employer.company.create')
                ->with('error', 'Session expired. Please start over.');
        }

        try {
            DB::beginTransaction();

            // Create company
            $company = Company::create([
                'name' => $data['name'],
                'slug' => \Str::slug($data['name']),
                'industry' => $data['industry'],
                'size' => $data['company_size'] ?? null,
                'founded_year' => $data['founded_year'] ?? null,
                'website' => $data['website'] ?? null,
                'location' => $data['location'],
                'headquarters' => $data['headquarters'] ?? null,
                'contact_email' => $data['contact_email'] ?? auth()->user()->email,
                'phone' => $data['phone'] ?? null,
                'description' => $data['description'],
                'logo_path' => $data['logo'] ?? null,
                'cover_path' => $data['cover_image'] ?? null,
                'culture_images' => json_encode([
                    'image1' => $data['culture_image_1'] ?? null,
                    'image2' => $data['culture_image_2'] ?? null,
                    'image3' => $data['culture_image_3'] ?? null,
                ]),
                'video_link' => $data['video_link'] ?? null,
                'social_links' => json_encode([
                    'facebook' => $data['facebook'] ?? null,
                    'twitter' => $data['twitter'] ?? null,
                    'linkedin' => $data['linkedin'] ?? null,
                ]),
                'owner_id' => auth()->id(),
                'verification_status' => 'pending',
            ]);

            // Attach user as owner in pivot table
            $company->teamMembers()->attach(auth()->id(), [
                'role' => 'owner',
                'is_active' => true,
            ]);

            // Assign employer role if not already
            if (!auth()->user()->hasRole('employer')) {
                auth()->user()->assignRole('employer');
            }

            DB::commit();

            // Clear session
            session()->forget('company_data');

            return redirect()->route('employer.company.success', $company)
                ->with('success', 'Company created successfully! Pending verification.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create company. Please try again.');
        }
    }

    /**
     * Show success page
     */
    public function success(Company $company)
    {
        return view('employer.company.success', compact('company'));
    }

    /**
 * Show company preview (public view)
 */
public function preview(Company $company)
{
    // Load relationships for the preview
    $company->load(['owner', 'jobPostings' => function ($query) {
        $query->where('status', 'active')
              ->latest()
              ->limit(3);
    }]);

    return view('employer.company.preview', compact('company'));
}
}