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
            
            {{-- Profile Completion Card (Static - will update on page reload) --}}
            <div class="mt-4 lg:mt-0 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 min-w-[250px]">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Strength</span>
                    <span class="text-xl font-bold text-red-600 dark:text-red-500">{{ $completion }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-red-600 dark:bg-red-500 h-2.5 rounded-full" style="width: {{ $completion }}%"></div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
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

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                        <a href="#photo" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Profile Photo
                            <span class="ml-auto text-xs {{ $user->profile_photo_path ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->profile_photo_path ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#basic" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Basic Information
                            <span class="ml-auto text-xs {{ $user->name && $user->email && $user->mobile ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->name && $user->email && $user->mobile ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#headline" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Professional Headline
                            <span class="ml-auto text-xs {{ $user->headline ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->headline ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#summary" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Professional Summary
                            <span class="ml-auto text-xs {{ $user->summary ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->summary ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#skills" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Skills
                            <span class="ml-auto text-xs {{ $user->skills->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->skills->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#education" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Education
                            <span class="ml-auto text-xs {{ $user->education->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->education->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#experience" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Work Experience
                            <span class="ml-auto text-xs {{ $user->experience->count() > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->experience->count() > 0 ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#resume" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Resume
                            <span class="ml-auto text-xs {{ $user->resume_path ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->resume_path ? '✓' : '○' }}
                            </span>
                        </a>
                        <a href="#preferences" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                            Job Preferences
                            <span class="ml-auto text-xs {{ $user->jobPreference ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $user->jobPreference ? '✓' : '○' }}
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
                                <img src="{{ $user->profile_photo_url }}" 
                                     alt="Profile Photo" 
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                Upload a professional photo to help employers recognize you
                            </p>
                            <p class="text-sm text-gray-500 mb-4">
                                JPG, PNG • Max 2MB • Recommended 400×400
                            </p>
                            
                            {{-- Photo Upload Form --}}
                            <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="inline">
                                @csrf
                                <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png" class="hidden" onchange="this.form.submit()">
                                <button type="button" onclick="document.getElementById('photo-input').click()" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                    Upload New Photo
                                </button>
                            </form>
                            
                            @if($user->profile_photo_path)
                                <form method="POST" action="{{ route('profile.photo.remove') }}" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove profile photo?')"
                                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                                        Remove Photo
                                    </button>
                                </form>
                            @endif
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
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address <span class="text-red-600">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mobile Number
                                </label>
                                <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                       placeholder="98XXXXXXXX">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date of Birth
                                </label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
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
                    <form method="POST" action="{{ route('profile.headline.update') }}">
                        @csrf
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Your headline appears next to your name in search results. Make it compelling!
                        </p>
                        <div class="flex gap-3">
                            <input type="text" name="headline" value="{{ $user->headline }}"
                                   placeholder="e.g. Senior Laravel Developer | 5+ Years Experience | React Specialist"
                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <button type="submit" 
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Save
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            {{ strlen($user->headline ?? '') }}/255 characters
                        </p>
                    </form>
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
                    <form method="POST" action="{{ route('profile.summary.update') }}">
                        @csrf
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Write a brief summary of your experience, skills, and career goals. 200-500 words recommended.
                        </p>
                        <textarea name="summary" rows="6"
                                  placeholder="Experienced professional with a passion for..."
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y">{{ $user->summary }}</textarea>
                        <div class="mt-3 flex justify-between items-center">
                            <p class="text-xs text-gray-500">
                                {{ strlen($user->summary ?? '') }}/5000 characters
                            </p>
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Save Summary
                            </button>
                        </div>
                    </form>
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
                    <form method="POST" action="{{ route('profile.skills.update') }}">
                        @csrf
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Add your key skills separated by commas. These help employers find you in searches.
                        </p>
                        
                        {{-- Skills Display --}}
                        @if($user->skills->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                @foreach($user->skills as $skill)
                                    <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Skills Input --}}
                        <div class="flex gap-2">
                            <input type="text" name="skills" 
                                   value="{{ implode(', ', $user->skills->pluck('name')->toArray()) }}"
                                   placeholder="e.g. Laravel, React, Project Management, PHP, MySQL"
                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <button type="submit" 
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Update Skills
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Enter skills separated by commas (e.g., Laravel, React, PHP)
                        </p>
                    </form>
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
                    <a href="{{ route('education.create') }}?return=profile" 
                       class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        + Add Education
                    </a>
                </div>
                <div class="p-6">
                    @if($user->education->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->education as $edu)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 relative">
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
                                            <a href="{{ route('education.edit', $edu) }}?return=profile" class="text-gray-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('education.destroy', $edu) }}" class="inline" onsubmit="return confirm('Delete this education entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
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
                    <a href="{{ route('experience.create') }}?return=profile" 
                       class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        + Add Experience
                    </a>
                </div>
                <div class="p-6">
                    @if($user->experience->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->experience as $exp)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 relative">
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
                                            <a href="{{ route('experience.edit', $exp) }}?return=profile" class="text-gray-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('experience.destroy', $exp) }}" class="inline" onsubmit="return confirm('Delete this experience entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
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
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center">
                        @if($user->resume_path)
                            <div class="mb-4">
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
                                <form method="POST" action="{{ route('profile.resume.delete') }}" class="inline" onsubmit="return confirm('Delete your resume?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                        Delete Resume
                                    </button>
                                </form>
                            </div>
                        @else
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Upload your resume (PDF, DOC, DOCX)</p>
                            <p class="text-sm text-gray-500 mb-4">Max file size: 5MB</p>
                            <form method="POST" action="{{ route('profile.resume.upload') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="resume" id="resume-input" accept=".pdf,.doc,.docx" class="hidden" onchange="this.form.submit()">
                                <button type="button" onclick="document.getElementById('resume-input').click()" 
                                        class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                    Choose File
                                </button>
                            </form>
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
                    <form method="POST" action="{{ route('profile.preferences.update') }}">
                        @csrf
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Set your job preferences to get better recommendations
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Preferred Locations
                                </label>
                                <input type="text" name="preferred_locations" 
                                       value="{{ $preferences['preferred_locations'] ?? '' }}"
                                       placeholder="e.g. Kathmandu, Pokhara, Remote"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Expected Salary (Monthly)
                                </label>
                                <input type="text" name="expected_salary" 
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
                                                   name="job_types[]" 
                                                   value="{{ $value }}" 
                                                   {{ in_array($value, $preferences['job_types'] ?? []) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="fresher" value="1" 
                                           {{ ($preferences['fresher'] ?? false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        I am a fresher (entry level)
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Save All Button (just scrolls to top) --}}
            <div class="flex justify-end sticky bottom-6">
                <a href="#top" 
                   class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-lg transition-colors">
                    Back to Top
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Minimal JavaScript only for file inputs and confirmations --}}
@section('scripts')
<script>
// Only used for file input triggers (these don't affect functionality)
function triggerFileInput(id) {
    document.getElementById(id).click();
}

// Confirm dialogs are handled by onsubmit attributes
</script>
@endsection