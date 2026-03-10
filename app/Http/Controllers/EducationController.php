<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    /**
     * Store a new education record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'degree'         => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'institution'    => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:2000',
        ]);

        Auth::user()->education()->create($validated);

        return redirect()->route('profile.edit')
            ->with('status', 'Education added successfully!');
    }

    /**
     * Remove an education record.
     */
    public function destroy(Education $education)
    {
        // Security: only owner can delete
        if ($education->user_id !== Auth::id()) {
            abort(403);
        }

        $education->delete();

        return redirect()->route('profile.edit')
            ->with('status', 'Education record removed.');
    }
}