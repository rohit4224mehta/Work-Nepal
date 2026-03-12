@extends('layouts.app')

@section('title', 'Edit Profile - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header with Profile Completion --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Your Profile</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                    Complete your profile to get noticed by top employers
                </p>
            </div>
            
            {{-- Profile Completion Card --}}
            <div class="mt-4 lg:mt-0 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 min-w-[250px]">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Strength</span>
                    <span class="text-xl font-bold text-red-600 dark:text-red-500" id="completion-percentage">{{ $completion }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-red-600 dark:bg-red-500 h-2.5 rounded-full transition-all duration-500" 
                         id="completion-bar"
                         style="width: {{ $completion }}%"></div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400" id="completion-message">
                    {{ $completion < 80 ? 'Add more details to reach 80%+' : 'Excellent! Your profile is ready.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Profile Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Sidebar - Navigation --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-24">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Sections</h2>
                    <nav class="space-y-2">
                        <a href="#photo" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Profile Photo
                            <span class="ml-auto text-xs {{ $user->profile_photo_path ? 'text-green-600' : 'text-gray-400' }}" id="photo-status">
                                {{ $user->profile_photo_path ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#basic" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Basic Information
                            <span class="ml-auto text-xs {{ $user->name && $user->email && $user->mobile ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->name && $user->email && $user->mobile ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#headline" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Professional Headline
                            <span class="ml-auto text-xs {{ $user->headline ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->headline ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#summary" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Professional Summary
                            <span class="ml-auto text-xs {{ $user->summary ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->summary ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#skills" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Skills
                            <span class="ml-auto text-xs {{ $user->skills->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->skills->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#education" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Education
                            <span class="ml-auto text-xs {{ $user->education->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->education->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#experience" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Work Experience
                            <span class="ml-auto text-xs {{ $user->experience->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->experience->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#resume" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Resume
                            <span class="ml-auto text-xs {{ $user->resume_path ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->resume_path ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#preferences" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors section-nav-link">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Job Preferences
                            <span class="ml-auto text-xs {{ $user->preferences ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->preferences ? '✓' : '○' }}
                            </span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Right Side - Profile Sections --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Profile Photo Section --}}
            <section id="photo" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Profile Photo
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-700">
                                <img id="profile-photo-preview" 
                                     src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                     alt="Profile Photo" 
                                     class="w-full h-full object-cover">
                            </div>
                            <button type="button" 
                                    onclick="document.getElementById('photo-input').click()"
                                    class="absolute bottom-0 right-0 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                Upload a professional photo to help employers recognize you
                            </p>
                            <p class="text-sm text-gray-500 mb-4">
                                JPG, PNG • Max 2MB • Recommended 400×400
                            </p>
                            <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                                <input type="file" id="photo-input" accept="image/jpeg,image/png" class="hidden">
                                <button type="button" 
                                        onclick="uploadPhoto()"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                    Upload New Photo
                                </button>
                                @if($user->profile_photo_path)
                                <button type="button" 
                                        onclick="removePhoto()"
                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                                    Remove Photo
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Basic Information Section --}}
            <section id="basic" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Basic Information
                    </h2>
                </div>
                <div class="p-6">
                    <form id="basic-info-form" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address <span class="text-red-600">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mobile Number
                                </label>
                                <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                       placeholder="98XXXXXXXX">
                                @error('mobile')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date of Birth
                                </label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Gender
                                </label>
                                <select name="gender" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                    <option value="">Prefer not to say</option>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Save Basic Information
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Professional Headline Section --}}
            <section id="headline" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Professional Headline
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Your headline appears next to your name in search results. Make it compelling!
                    </p>
                    <div class="flex gap-3">
                        <input type="text" id="headline-input" value="{{ $user->headline }}"
                               placeholder="e.g. Senior Laravel Developer | 5+ Years Experience | React Specialist"
                               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <button type="button" 
                                onclick="updateHeadline()"
                                class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                            Save
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        <span id="headline-count">{{ strlen($user->headline ?? '') }}</span>/255 characters
                    </p>
                </div>
            </section>

            {{-- Professional Summary Section --}}
            <section id="summary" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Professional Summary
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Write a brief summary of your experience, skills, and career goals. 200-500 words recommended.
                    </p>
                    <textarea id="summary-input" rows="6"
                              placeholder="Experienced professional with a passion for..."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y">{{ $user->summary }}</textarea>
                    <div class="mt-3 flex justify-between items-center">
                        <p class="text-xs text-gray-500">
                            <span id="summary-count">{{ strlen($user->summary ?? '') }}</span>/5000 characters
                        </p>
                        <button type="button" 
                                onclick="updateSummary()"
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Save Summary
                        </button>
                    </div>
                </div>
            </section>

            {{-- Skills Section --}}
            <section id="skills" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Skills
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Add your key skills. These help employers find you in searches.
                    </p>
                    
                    {{-- Skills Tags --}}
                    <div class="flex flex-wrap gap-2 mb-4 min-h-[60px] p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl" id="skills-container">
                        @foreach($userSkills as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                {{ $skill }}
                                <button type="button" onclick="removeSkill('{{ $skill }}')" class="ml-2 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                        @if(empty($userSkills))
                            <p class="text-gray-500 text-sm">No skills added yet. Add your first skill below.</p>
                        @endif
                    </div>

                    {{-- Add Skill Input --}}
                    <div class="flex gap-2">
                        <input type="text" id="new-skill-input" 
                               placeholder="e.g. Laravel, React, Project Management"
                               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <button type="button" 
                                onclick="addSkill()"
                                class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                            Add
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Press Enter or click Add to add a skill. Click on skills to remove.
                    </p>
                </div>
            </section>

            {{-- Education Section --}}
            <section id="education" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        Education
                    </h2>
                    <button type="button" 
                            onclick="showEducationModal()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        + Add Education
                    </button>
                </div>
                <div class="p-6">
                    @if($user->education->count() > 0)
                        <div class="space-y-4" id="education-list">
                            @foreach($user->education as $edu)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 relative education-item" data-id="{{ $edu->id }}">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $edu->degree }} in {{ $edu->field_of_study }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $edu->institution }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} - 
                                                {{ $edu->is_current ? 'Present' : \Carbon\Carbon::parse($edu->end_date)->format('Y') }}
                                            </p>
                                            @if($edu->description)
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $edu->description }}</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="editEducation({{ $edu->id }})" class="text-gray-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteEducation({{ $edu->id }})" class="text-gray-500 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">No education added yet. Click "Add Education" to get started.</p>
                    @endif
                </div>
            </section>

            {{-- Experience Section --}}
            <section id="experience" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Work Experience
                    </h2>
                    <button type="button" 
                            onclick="showExperienceModal()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        + Add Experience
                    </button>
                </div>
                <div class="p-6">
                    @if($user->experience->count() > 0)
                        <div class="space-y-4" id="experience-list">
                            @foreach($user->experience as $exp)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 relative experience-item" data-id="{{ $exp->id }}">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $exp->position }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $exp->company_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - 
                                                {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                            </p>
                                            @if($exp->description)
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($exp->description, 150) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="editExperience({{ $exp->id }})" class="text-gray-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteExperience({{ $exp->id }})" class="text-gray-500 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">No experience added yet. Click "Add Experience" to get started.</p>
                    @endif
                </div>
            </section>

            {{-- Resume Section --}}
            <section id="resume" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Resume / CV
                    </h2>
                </div>
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center" id="resume-upload-area">
                        @if($user->resume_path)
                            <div id="resume-info" class="mb-4">
                                <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-900 dark:text-white font-medium">Resume Uploaded</p>
                                <p class="text-sm text-gray-500 mt-1">{{ basename($user->resume_path) }}</p>
                            </div>
                            <div class="flex justify-center gap-3">
                                <a href="{{ Storage::url($user->resume_path) }}" target="_blank"
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    View Resume
                                </a>
                                <button onclick="deleteResume()"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                    Delete Resume
                                </button>
                            </div>
                        @else
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Upload your resume (PDF, DOC, DOCX)</p>
                            <p class="text-sm text-gray-500 mb-4">Max file size: 5MB</p>
                            <input type="file" id="resume-input" accept=".pdf,.doc,.docx" class="hidden">
                            <button type="button" 
                                    onclick="document.getElementById('resume-input').click()"
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Choose File
                            </button>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Job Preferences Section --}}
            <section id="preferences" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Job Preferences
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Set your job preferences to get better recommendations
                    </p>
                    
                    <form id="preferences-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Preferred Locations
                                </label>
                                <input type="text" id="preferred-locations" 
                                       value="{{ $preferences['preferred_locations'] ?? '' }}"
                                       placeholder="e.g. Kathmandu, Pokhara, Remote"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Expected Salary (Monthly)
                                </label>
                                <input type="number" id="expected-salary" 
                                       value="{{ $preferences['expected_salary'] ?? '' }}"
                                       placeholder="e.g. 50000"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Job Types
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($jobTypes as $value => $label)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   value="{{ $value }}" 
                                                   {{ in_array($value, $preferences['job_types'] ?? []) ? 'checked' : '' }}
                                                   class="job-type-checkbox w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="open-to-relocation" 
                                           {{ ($preferences['open_to_relocation'] ?? false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        I am open to relocation
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" 
                                    onclick="updatePreferences()"
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Save All Button --}}
            <div class="flex justify-end sticky bottom-6">
                <button type="button" 
                        onclick="saveAllSections()"
                        class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg transition-colors">
                    Save All Changes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Education Modal --}}
<div id="education-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="education-modal-title">Add Education</h3>
            <button onclick="hideEducationModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="education-form">
                @csrf
                <input type="hidden" id="education-id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Degree *</label>
                        <input type="text" id="degree" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Field of Study *</label>
                        <input type="text" id="field-of-study" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Institution *</label>
                        <input type="text" id="institution" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" id="location"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                            <input type="date" id="start-date" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" id="end-date"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="is-current" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I am currently studying here</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            onclick="hideEducationModal()"
                            class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Save Education
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Experience Modal --}}
<div id="experience-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="experience-modal-title">Add Experience</h3>
            <button onclick="hideExperienceModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="experience-form">
                @csrf
                <input type="hidden" id="experience-id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Title *</label>
                        <input type="text" id="position" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name *</label>
                        <input type="text" id="company-name" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" id="experience-location"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                            <input type="date" id="experience-start-date" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" id="experience-end-date"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="experience-is-current" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I currently work here</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="experience-description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y"
                                  placeholder="Describe your responsibilities and achievements..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            onclick="hideExperienceModal()"
                            class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Save Experience
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Profile Completion Update
function updateProfileCompletion() {
    fetch('{{ route("profile.completion") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('completion-percentage').textContent = data.completion + '%';
                document.getElementById('completion-bar').style.width = data.completion + '%';
                document.getElementById('completion-message').textContent = data.message;
            }
        });
}

// Photo Upload
document.getElementById('photo-input').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        uploadPhoto();
    }
});

function uploadPhoto() {
    const file = document.getElementById('photo-input').files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("profile.photo.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('profile-photo-preview').src = data.photo_url + '?t=' + Date.now();
            document.getElementById('photo-status').innerHTML = '✓';
            document.getElementById('photo-status').className = 'ml-auto text-xs text-green-600';
            updateProfileCompletion();
            showNotification('success', data.message);
        }
    });
}

function removePhoto() {
    if (!confirm('Are you sure you want to remove your profile photo?')) return;

    fetch('{{ route("profile.photo.remove") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('profile-photo-preview').src = '{{ asset("images/default-avatar.png") }}';
            document.getElementById('photo-status').innerHTML = '○';
            document.getElementById('photo-status').className = 'ml-auto text-xs text-gray-400';
            updateProfileCompletion();
            showNotification('success', data.message);
        }
    });
}

// Headline
document.getElementById('headline-input').addEventListener('input', function() {
    document.getElementById('headline-count').textContent = this.value.length;
});

function updateHeadline() {
    const headline = document.getElementById('headline-input').value;

    fetch('{{ route("profile.headline.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ headline: headline })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            updateProfileCompletion();
        }
    });
}

// Summary
document.getElementById('summary-input').addEventListener('input', function() {
    document.getElementById('summary-count').textContent = this.value.length;
});

function updateSummary() {
    const summary = document.getElementById('summary-input').value;

    fetch('{{ route("profile.summary.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ summary: summary })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            updateProfileCompletion();
        }
    });
}

// Skills
document.getElementById('new-skill-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkill();
    }
});

function addSkill() {
    const input = document.getElementById('new-skill-input');
    const skill = input.value.trim();
    
    if (skill === '') {
        showNotification('error', 'Please enter a skill');
        return;
    }

    // Get current skills
    const skills = [];
    document.querySelectorAll('#skills-container span').forEach(span => {
        const skillText = span.textContent.trim();
        if (skillText !== 'No skills added yet. Add your first skill below.') {
            skills.push(skillText);
        }
    });
    
    if (!skills.includes(skill)) {
        skills.push(skill);
        saveSkills(skills);
        input.value = '';
    } else {
        showNotification('error', 'This skill already exists');
    }
}

function removeSkill(skill) {
    const skills = [];
    document.querySelectorAll('#skills-container span').forEach(span => {
        const skillText = span.textContent.trim();
        if (skillText !== skill && skillText !== 'No skills added yet. Add your first skill below.') {
            skills.push(skillText);
        }
    });
    saveSkills(skills);
}

function saveSkills(skills) {
    fetch('{{ route("profile.skills.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ skills: skills })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderSkills(data.skills);
            updateProfileCompletion();
            showNotification('success', data.message);
        }
    });
}

function renderSkills(skills) {
    const container = document.getElementById('skills-container');
    if (skills.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">No skills added yet. Add your first skill below.</p>';
        return;
    }

    let html = '';
    skills.forEach(skill => {
        html += `<span class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
            ${skill}
            <button type="button" onclick="removeSkill('${skill}')" class="ml-2 hover:text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </span>`;
    });
    container.innerHTML = html;
}

// Education
// Reset education form when opening for new entry
function showEducationModal() {
    document.getElementById('education-form').reset();
    document.getElementById('education-id').value = '';
    document.getElementById('is-current').checked = false;
    document.getElementById('education-modal-title').textContent = 'Add Education';
    document.getElementById('education-modal').classList.remove('hidden');
    document.getElementById('education-modal').classList.add('flex');
}


function hideEducationModal() {
    document.getElementById('education-modal').classList.add('hidden');
    document.getElementById('education-modal').classList.remove('flex');
}

document.getElementById('education-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const educationId = document.getElementById('education-id').value;
    const url = educationId ? `/education/${educationId}` : '{{ route("education.store") }}';
    const method = educationId ? 'PUT' : 'POST';

    const formData = {
        _token: '{{ csrf_token() }}',
        degree: document.getElementById('degree').value,
        field_of_study: document.getElementById('field-of-study').value,
        institution: document.getElementById('institution').value,
        location: document.getElementById('location').value,
        start_date: document.getElementById('start-date').value,
        end_date: document.getElementById('end-date').value,
        is_current: document.getElementById('is-current').checked,
        description: document.getElementById('description').value
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideEducationModal();
            location.reload(); // Simple reload for now
        }
    });
});

function editEducation(id) {
    fetch(`/education/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('education-id').value = data.education.id;
                document.getElementById('degree').value = data.education.degree;
                document.getElementById('field-of-study').value = data.education.field_of_study;
                document.getElementById('institution').value = data.education.institution;
                document.getElementById('location').value = data.education.location || '';
                document.getElementById('start-date').value = data.education.start_date;
                document.getElementById('end-date').value = data.education.end_date || '';
                document.getElementById('is-current').checked = data.education.is_current == 1;
                document.getElementById('description').value = data.education.description || '';
                
                document.getElementById('education-modal-title').textContent = 'Edit Education';
                document.getElementById('education-modal').classList.remove('hidden');
                document.getElementById('education-modal').classList.add('flex');
            }
        })
        .catch(error => {
            showNotification('error', 'Failed to load education data');
        });
}
function deleteEducation(id) {
    if (!confirm('Are you sure you want to delete this education entry?')) return;

    fetch(`/education/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
// Update education form submission
document.getElementById('education-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const educationId = document.getElementById('education-id').value;
    const url = educationId ? `/education/${educationId}` : '{{ route("education.store") }}';
    const method = educationId ? 'PUT' : 'POST';

    const formData = {
        degree: document.getElementById('degree').value,
        field_of_study: document.getElementById('field-of-study').value,
        institution: document.getElementById('institution').value,
        location: document.getElementById('location').value,
        start_date: document.getElementById('start-date').value,
        end_date: document.getElementById('end-date').value,
        is_current: document.getElementById('is-current').checked ? 1 : 0,
        description: document.getElementById('description').value
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideEducationModal();
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message || 'Operation failed');
        }
    })
    .catch(error => {
        showNotification('error', 'Network error. Please try again.');
    });
});

// Update experience form submission
document.getElementById('experience-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const experienceId = document.getElementById('experience-id').value;
    const url = experienceId ? `/experience/${experienceId}` : '{{ route("experience.store") }}';
    const method = experienceId ? 'PUT' : 'POST';

    const formData = {
        position: document.getElementById('position').value,
        company_name: document.getElementById('company-name').value,
        location: document.getElementById('experience-location').value,
        start_date: document.getElementById('experience-start-date').value,
        end_date: document.getElementById('experience-end-date').value,
        is_current: document.getElementById('experience-is-current').checked ? 1 : 0,
        description: document.getElementById('experience-description').value
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideExperienceModal();
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message || 'Operation failed');
        }
    })
    .catch(error => {
        showNotification('error', 'Network error. Please try again.');
    });
});

// Experience
function showExperienceModal() {
    document.getElementById('experience-form').reset();
    document.getElementById('experience-id').value = '';
    document.getElementById('experience-is-current').checked = false;
    document.getElementById('experience-modal-title').textContent = 'Add Experience';
    document.getElementById('experience-modal').classList.remove('hidden');
    document.getElementById('experience-modal').classList.add('flex');
}

function hideExperienceModal() {
    document.getElementById('experience-modal').classList.add('hidden');
    document.getElementById('experience-modal').classList.remove('flex');
}

document.getElementById('experience-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const experienceId = document.getElementById('experience-id').value;
    const url = experienceId ? `/experience/${experienceId}` : '{{ route("experience.store") }}';
    const method = experienceId ? 'PUT' : 'POST';

    const formData = {
        _token: '{{ csrf_token() }}',
        position: document.getElementById('position').value,
        company_name: document.getElementById('company-name').value,
        location: document.getElementById('experience-location').value,
        start_date: document.getElementById('experience-start-date').value,
        end_date: document.getElementById('experience-end-date').value,
        is_current: document.getElementById('experience-is-current').checked,
        description: document.getElementById('experience-description').value
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideExperienceModal();
            location.reload(); // Simple reload for now
        }
    });
});

// Edit Experience
function editExperience(id) {
    fetch(`/experience/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('experience-id').value = data.experience.id;
                document.getElementById('position').value = data.experience.position;
                document.getElementById('company-name').value = data.experience.company_name;
                document.getElementById('experience-location').value = data.experience.location || '';
                document.getElementById('experience-start-date').value = data.experience.start_date;
                document.getElementById('experience-end-date').value = data.experience.end_date || '';
                document.getElementById('experience-is-current').checked = data.experience.is_current == 1;
                document.getElementById('experience-description').value = data.experience.description || '';
                
                document.getElementById('experience-modal-title').textContent = 'Edit Experience';
                document.getElementById('experience-modal').classList.remove('hidden');
                document.getElementById('experience-modal').classList.add('flex');
            }
        })
        .catch(error => {
            showNotification('error', 'Failed to load experience data');
        });
}

function deleteExperience(id) {
    if (!confirm('Are you sure you want to delete this experience entry?')) return;

    fetch(`/experience/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Resume
document.getElementById('resume-input').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        uploadResume();
    }
});

function uploadResume() {
    const file = document.getElementById('resume-input').files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('resume', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("profile.resume.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteResume() {
    if (!confirm('Are you sure you want to delete your resume?')) return;

    fetch('{{ route("profile.resume.delete") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Preferences
function updatePreferences() {
    const jobTypes = [];
    document.querySelectorAll('.job-type-checkbox:checked').forEach(cb => {
        jobTypes.push(cb.value);
    });

    const data = {
        _token: '{{ csrf_token() }}',
        preferred_locations: document.getElementById('preferred-locations').value,
        job_types: jobTypes,
        expected_salary: document.getElementById('expected-salary').value,
        open_to_relocation: document.getElementById('open-to-relocation').checked
    };

    fetch('{{ route("profile.preferences.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            updateProfileCompletion();
        }
    });
}

// Save All Sections
function saveAllSections() {
    // Submit all forms
    document.getElementById('basic-info-form').submit();
    
    // Other sections are saved via AJAX, so we just show a message
    showNotification('info', 'All changes saved successfully!');
}

// Notification System
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Character counters
document.addEventListener('DOMContentLoaded', function() {
    // Highlight active section on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.section-nav-link');
    
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            if (pageYOffset >= sectionTop) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('text-red-600', 'font-medium');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('text-red-600', 'font-medium');
            }
        });
    });
});
</script>
@endsection