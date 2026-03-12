<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create()
    {
        return view('employer.company.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'description' => 'nullable|string'
        ]);

        $company = Company::create([
            'owner_id' => $user->id,
            'name' => $data['name'],
            'industry' => $data['industry'] ?? null,
            'location' => $data['location'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null
        ]);

        // attach user to company
        $user->companies()->attach($company->id, [
            'role' => 'owner',
            'is_active' => true
        ]);

        // assign employer role
        if (!$user->hasRole('employer')) {
            $user->assignRole('employer');
        }

        return redirect()->route('employer.dashboard')
            ->with('success', 'Company profile created successfully.');
    }
}