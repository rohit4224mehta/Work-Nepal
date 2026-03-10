@extends('layouts.app')

@section('title', 'Edit Profile - WorkNepal')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 bg-gray-50 min-h-screen">

    <!-- Header + Progress -->
    <div class="mb-12 text-center lg:text-left">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">
            Edit Your Profile
        </h1>
        <p class="mt-3 text-lg text-gray-600">
            A complete profile increases your visibility to recruiters and improves job recommendations.
        </p>

        <!-- Profile Completion (placeholder - calculate in controller later) -->
        @php $completion = 65; @endphp
        <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-3">
                <span class="text-lg font-medium text-gray-800">Profile Strength</span>
                <span class="text-xl font-bold text-red-600">{{ $completion }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-red-600 h-3 rounded-full transition-all duration-500"
                     style="width: {{ $completion }}%"></div>
            </div>
            <p class="mt-3 text-sm text-gray-600">
                {{ $completion < 80 ? 'Add skills, experience & preferences to reach 80%+' : 'Excellent! Your profile is ready to impress.' }}
            </p>
        </div>
    </div>

    <!-- Flash Messages -->
    @include('partials.flash-messages')

    <!-- Main Profile Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-10">
        @csrf
        @method('PATCH')

        <!-- 1. Photo + Headline -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <div class="flex flex-col lg:flex-row gap-10 items-start lg:items-center">
                    <!-- Photo -->
                    <div class="flex flex-col items-center lg:items-start">
                        <label class="text-xl font-semibold text-gray-900 mb-4">Profile Photo</label>
                        <div class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-gray-200 shadow-inner bg-gray-50">
                            <img id="photo-preview"
                                 src="{{ auth()->user()->profilePhotoUrl ?? asset('images/default-avatar.png') }}"
                                 alt="Your Photo"
                                 class="w-full h-full object-cover">

                            <label for="photo"
                                   class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                        </div>

                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" class="hidden mt-4">
                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-3 text-sm text-gray-500">JPG/PNG • Max 2MB • Recommended 400×400</p>
                    </div>

                    <!-- Headline -->
                    <div class="flex-1 w-full">
                        <label for="headline" class="block text-xl font-semibold text-gray-900 mb-4">
                            Professional Headline
                        </label>
                        <input type="text" name="headline" id="headline"
                               value="{{ old('headline', auth()->user()->headline ?? '') }}"
                               placeholder="e.g. Full-Stack Developer | Laravel & React | 3+ years"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-lg">
                        @error('headline')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Basic Information -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="block text-base font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" required value="{{ old('name', auth()->user()->name) }}"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mobile" class="block text-base font-medium text-gray-700 mb-2">
                            Mobile Number
                        </label>
                        <input type="tel" name="mobile" value="{{ old('mobile', auth()->user()->mobile) }}"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        @error('mobile') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-base font-medium text-gray-700 mb-2">
                            Gender
                        </label>
                        <select name="gender" class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-white">
                            <option value="">Prefer not to say</option>
                            <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-base font-medium text-gray-700 mb-2">
                            Date of Birth
                        </label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        @error('date_of_birth') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Professional Summary -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Professional Summary</h2>
                <textarea name="summary" rows="6"
                          class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-y min-h-[160px]"
                          placeholder="Write about your experience, skills, achievements, and career goals...">{{ old('summary', auth()->user()->summary ?? '') }}</textarea>
                @error('summary') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- 4. Skills (Tag Input) -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Skills</h2>

                <!-- Current Skills -->
                <div class="flex flex-wrap gap-3 mb-8">
                    @if(auth()->user()->skills?->isNotEmpty())
                        @foreach(auth()->user()->skills as $skill)
                            <span class="px-5 py-2 bg-blue-100 text-blue-800 rounded-full text-base font-medium flex items-center gap-2">
                                {{ $skill }}
                                <button type="button" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                        
                    @else
                        <p class="text-gray-600">No skills added yet. Add your top skills below.</p>
                    @endif
                </div>

                <!-- Add Skill -->
                <div class="flex gap-4">
                    <input type="text" name="new_skill" id="new_skill"
                           placeholder="e.g. Laravel, React, SQL, UI/UX" 
                           class="flex-1 px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                    <button type="button" onclick="addSkill()"
                            class="px-8 py-5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
                        Add Skill
                    </button>
                </div>

                <!-- Hidden input for skills array -->
                <input type="hidden" name="skills" id="skills-input" value="{{ old('skills', $skillsString) }}">
            </div>
        </div>

        <!-- 5. Experience (Repeatable) -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Experience</h2>

                <!-- Existing Entries -->
                @if(auth()->user()->experience?->isNotEmpty())
                    <div class="space-y-6 mb-12">
                        @foreach(auth()->user()->experience as $exp)
                            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 relative">
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $exp->position }} at {{ $exp->company }}
                                        </h3>
                                        <p class="text-gray-700 mt-1">
                                            {{ $exp->location ?? 'Remote' }} • {{ $exp->duration }}
                                        </p>
                                        @if($exp->description)
                                            <p class="mt-3 text-gray-600">{{ $exp->description }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('experience.destroy', $exp) }}" class="mt-4 sm:mt-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-5 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-8">
                        No experience added yet. Add your work history below.
                    </p>
                @endif

                <!-- Add New Experience -->
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Add New Experience</h3>
                <form method="POST" action="{{ route('experience.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Job Title <span class="text-red-600">*</span></label>
                            <input type="text" name="position" required class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Company Name <span class="text-red-600">*</span></label>
                            <input type="text" name="company" required class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" name="location" class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Currently Working Here</label>
                            <label class="inline-flex items-center mt-3">
                                <input type="checkbox" name="is_current" value="1" class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-3 text-base text-gray-700">Yes, I currently work here</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Start Date <span class="text-red-600">*</span></label>
                            <input type="date" name="start_date" required class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" name="end_date" class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-base font-medium text-gray-700 mb-2">Description / Responsibilities</label>
                        <textarea name="description" rows="5"
                                  class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-y"></textarea>
                    </div>

                    <div class="mt-10">
                        <button type="submit" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-medium text-lg rounded-xl shadow-lg transition-colors">
                            Add Experience
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 6. Job Preferences -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Job Preferences</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Preferred Locations</label>
                        <input type="text" name="preferred_locations" placeholder="e.g. Ahmedabad, Remote, Surat"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Preferred Job Types</label>
                        <select name="job_types[]" multiple class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="internship">Internship</option>
                            <option value="freelance">Freelance / Contract</option>
                            <option value="remote">Remote</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Expected Salary (₹ per month)</label>
                        <input type="number" name="expected_salary" placeholder="e.g. 50000"
                               class="w-full px-6 py-5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">Open to Relocation</label>
                        <label class="inline-flex items-center mt-3">
                            <input type="checkbox" name="open_to_relocation" value="1" class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-3 text-base text-gray-700">Yes, willing to relocate</span>
                        </label>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-medium text-lg rounded-xl shadow-lg transition-colors">
                        Save Preferences
                    </button>
                </div>
            </div>
        </div>

        <!-- Final Save Button -->
        <div class="mt-12 flex justify-center lg:justify-end">
            <button type="submit" class="px-12 py-6 bg-red-600 hover:bg-red-700 text-white font-bold text-xl rounded-2xl shadow-2xl transition-all transform hover:scale-105">
                Save All Changes
            </button>
        </div>

    </form>

</div>
@endsection

@section('scripts')
<script>
// Photo Preview
document.getElementById('photo')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection