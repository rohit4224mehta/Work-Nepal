<?php
// app/Http/Controllers/Employer/CompanyController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- IMPORT THIS
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Show step 1 - Basic Information
     */
    public function create()
    {
        // Clear any existing session data when starting fresh
        session()->forget('company_data');
        
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
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'website' => 'nullable|url|max:255',
        ]);

        // Store in session
        session(['company_data' => array_merge(session('company_data', []), $request->all())]);

        return redirect()->route('employer.company.details')
            ->with('success', 'Step 1 completed! Please provide more details.');
    }

    /**
     * Show step 2 - Company Details
     */
    public function details()
    {
        $companyData = session('company_data');
        
        // Check if step 1 is completed
        if (empty($companyData['company_name'])) {
            return redirect()->route('employer.company.create')
                ->with('error', 'Please complete step 1 first.');
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
        $socialLinks = [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
        ];

        session(['company_data' => array_merge(session('company_data', []), $request->except(['facebook', 'twitter', 'linkedin']))]);
        session(['company_data.social_links' => $socialLinks]);

        return redirect()->route('employer.company.branding')
            ->with('success', 'Step 2 completed! Now add your branding.');
    }

    /**
     * Show step 3 - Branding
     */
    public function branding()
    {
        $companyData = session('company_data');
        
        // Check if step 2 is completed
        if (empty($companyData['location'])) {
            return redirect()->route('employer.company.details')
                ->with('error', 'Please complete step 2 first.');
        }

        return view('employer.company.branding');
    }

    /**
     * Store step 3 data (file uploads)
     */
    public function storeStep3(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            $uploadedFiles['logo_path'] = $path;
        }

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('company-covers', 'public');
            $uploadedFiles['cover_path'] = $path;
        }

        // Handle culture images
        $cultureImages = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("culture_image_$i")) {
                $path = $request->file("culture_image_$i")->store('company-culture', 'public');
                $cultureImages[] = $path;
            }
        }
        if (!empty($cultureImages)) {
            $uploadedFiles['culture_images'] = $cultureImages;
        }

        // Handle video link
        if ($request->filled('video_link')) {
            $uploadedFiles['video_link'] = $request->video_link;
        }

        // Store in session
        session(['company_data' => array_merge(session('company_data', []), $uploadedFiles)]);

        return redirect()->route('employer.company.review')
            ->with('success', 'Step 3 completed! Review your company profile.');
    }

    /**
     * Show step 4 - Review
     */
    public function review()
    {
        $companyData = session('company_data');
        
        // Check if all steps are completed
        if (empty($companyData['company_name']) || empty($companyData['location']) || empty($companyData['description'])) {
            return redirect()->route('employer.company.create')
                ->with('error', 'Please complete all steps first.');
        }

        return view('employer.company.review', compact('companyData'));
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
                'name' => $data['company_name'],
                'slug' => Str::slug($data['company_name']),
                'industry' => $data['industry'],
                'size' => $data['company_size'] ?? null,
                'founded_year' => $data['founded_year'] ?? null,
                'website' => $data['website'] ?? null,
                'location' => $data['location'],
                'headquarters' => $data['headquarters'] ?? null,
                'contact_email' => $data['contact_email'] ?? auth()->user()->email,
                'phone' => $data['phone'] ?? null,
                'description' => $data['description'],
                'logo_path' => $data['logo_path'] ?? null,
                'cover_path' => $data['cover_path'] ?? null,
                'culture_images' => json_encode($data['culture_images'] ?? []),
                'video_link' => $data['video_link'] ?? null,
                'social_links' => json_encode($data['social_links'] ?? []),
                'owner_id' => auth()->id(),
                'verification_status' => 'pending',
            ]);

            // Attach user as owner in company_team_members table
            $company->teamMembers()->attach(auth()->id(), [
                'role' => 'owner',
                'is_active' => true,
                'permissions' => json_encode(['all' => true]),
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
            \Log::error('Company creation failed: ' . $e->getMessage());
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
     * Show company preview
     */
    public function preview(Company $company)
    {
        // Check if user can view this company
        if (!auth()->user()->canAccessCompany($company) && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $company->load(['owner', 'jobPostings' => function ($query) {
            $query->where('status', 'active')
                  ->latest()
                  ->limit(5);
        }]);

        return view('employer.company.preview', compact('company'));
    }

    /**
     * Show the form for editing company profile
     */
    public function edit(Company $company)
    {
        // Check if user owns this company
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this company.');
        }
        
        return view('employer.company.edit', compact('company'));
    }
    
    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company)
    {
        // Check if user owns this company
        if ($company->owner_id !== Auth::id()) {
            abort(403, 'You do not have permission to update this company.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'location' => 'nullable|string|max:255',
            'headquarters' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.twitter' => 'nullable|url|max:255',
            'social_links.linkedin' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
        ]);
        
        // Prepare data for update
        $updateData = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'industry' => $validated['industry'] ?? $company->industry,
            'size' => $validated['size'] ?? $company->size,
            'founded_year' => $validated['founded_year'] ?? $company->founded_year,
            'location' => $validated['location'] ?? $company->location,
            'headquarters' => $validated['headquarters'] ?? $company->headquarters,
            'website' => $validated['website'] ?? $company->website,
            'contact_email' => $validated['contact_email'] ?? $company->contact_email,
            'phone' => $validated['phone'] ?? $company->phone,
            'description' => $validated['description'] ?? $company->description,
        ];
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $updateData['logo_path'] = $logoPath;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($company->cover_path) {
                Storage::disk('public')->delete($company->cover_path);
            }
            
            $coverPath = $request->file('cover')->store('company-covers', 'public');
            $updateData['cover_path'] = $coverPath;
        }
        
        // Handle social links
        if (isset($validated['social_links'])) {
            $updateData['social_links'] = json_encode(array_filter($validated['social_links']));
        }
        
        // Update the company
        $company->update($updateData);
        
        // Log the activity (if activity log is installed)
        if (class_exists('Spatie\Activitylog\ActivitylogServiceProvider')) {
            activity()
                ->performedOn($company)
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Company profile updated');
        }
        
        return redirect()
            ->route('employer.dashboard')
            ->with('success', 'Company profile updated successfully!');
    }
    
    /**
     * Show team management page
     */
    public function team(Company $company)
    {
        // Check if user can manage this company
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403);
        }

        $company->load(['owner', 'teamMembers']);

        return view('employer.company.team', compact('company'));
    }

    /**
     * Add team member to company
     */
    public function addTeamMember(Request $request, Company $company)
    {
        // Check if user can manage this company
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:recruiter,hr,manager,admin',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if already a member
        if ($company->teamMembers()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a team member');
        }

        // Don't allow adding owner again
        if ($user->id === $company->owner_id) {
            return back()->with('error', 'User is already the company owner');
        }

        // Add to company
        $company->teamMembers()->attach($user->id, [
            'role' => $request->role,
            'is_active' => true,
            'permissions' => json_encode(['manage_jobs' => true, 'view_applications' => true]),
        ]);

        // Assign employer role if needed
        if (!$user->hasRole('employer')) {
            $user->assignRole('employer');
        }

        return back()->with('success', 'Team member added successfully');
    }

    /**
     * Remove team member from company
     */
    public function removeTeamMember(Company $company, User $user)
    {
        // Check if user can manage this company
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403);
        }

        // Cannot remove owner
        if ($user->id === $company->owner_id) {
            return back()->with('error', 'Cannot remove the company owner');
        }

        $company->teamMembers()->detach($user->id);

        return back()->with('success', 'Team member removed successfully');
    }

    /**
     * Update team member role
     */
    public function updateTeamMemberRole(Request $request, Company $company, User $user)
    {
        // Check if user can manage this company
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:recruiter,hr,manager,admin',
        ]);

        $company->teamMembers()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);

        return back()->with('success', 'Team member role updated successfully');
    }

    /**
     * Toggle team member active status
     */
    public function toggleTeamMemberStatus(Company $company, User $user)
    {
        // Check if user can manage this company
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403);
        }

        $pivot = $company->teamMembers()->where('user_id', $user->id)->first();
        
        if ($pivot) {
            $newStatus = !$pivot->pivot->is_active;
            $company->teamMembers()->updateExistingPivot($user->id, [
                'is_active' => $newStatus,
            ]);
            
            $message = $newStatus ? 'Team member activated' : 'Team member deactivated';
            return back()->with('success', $message);
        }

        return back()->with('error', 'Team member not found');
    }
}