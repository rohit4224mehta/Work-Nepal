@extends('layouts.app')

@section('title', 'Create Company - Step 4: Review & Submit - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Review Your Company Profile
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Please review all information before submitting
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-12">
        <div class="flex items-center justify-center">
            {{-- Step 1 Complete --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Basic</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-green-600"></div>
            
            {{-- Step 2 Complete --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Details</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-green-600"></div>
            
            {{-- Step 3 Complete --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Branding</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-green-600"></div>
            
            {{-- Step 4 Active --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Review</span>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Step 4: Review & Submit</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Verify all information before creating your company</p>
                </div>
            </div>
        </div>

        {{-- Review Content --}}
        <div class="p-6 lg:p-8">
            
            {{-- Company Preview Card --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-white dark:bg-gray-700 flex items-center justify-center overflow-hidden border-2 border-gray-200 dark:border-gray-600">
                        @if(session('company_data.logo'))
                            <img src="{{ session('company_data.logo') }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-gray-400">{{ substr(session('company_data.name', 'C'), 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ session('company_data.name') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ session('company_data.industry') }} • {{ session('company_data.location') }}</p>
                    </div>
                </div>
            </div>

            {{-- Review Sections --}}
            <div class="space-y-8">
                
                {{-- Section 1: Basic Info --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                        <a href="{{ route('employer.company.create') }}" class="text-sm text-red-600 hover:text-red-700">
                            Edit
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Company Name</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.name') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Industry</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.industry') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Company Size</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.company_size') ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Founded</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.founded_year') ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Website</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.website') ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Company Details --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Company Details</h3>
                        <a href="{{ route('employer.company.details') }}" class="text-sm text-red-600 hover:text-red-700">
                            Edit
                        </a>
                    </div>
                    <div class="space-y-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Location</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.location') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Headquarters</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.headquarters') ?? 'Same as location' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Contact Email</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.contact_email') ?? auth()->user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ session('company_data.phone') ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Description</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ session('company_data.description') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Branding --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Branding</h3>
                        <a href="{{ route('employer.company.branding') }}" class="text-sm text-red-600 hover:text-red-700">
                            Edit
                        </a>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Logo</p>
                                <div class="w-16 h-16 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center border">
                                    @if(session('company_data.logo'))
                                        <span class="text-xs text-green-600">✓ Uploaded</span>
                                    @else
                                        <span class="text-xs text-gray-400">Not uploaded</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Cover Image</p>
                                <span class="text-xs {{ session('company_data.cover_image') ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ session('company_data.cover_image') ? '✓ Uploaded' : 'Not uploaded' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Culture Photos</p>
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ session('company_data.culture_photos_count') ?? 0 }}/3 uploaded
                                </span>
                            </div>
                        </div>
                        @if(session('company_data.video_link'))
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Video Link</p>
                                <p class="text-sm text-gray-900 dark:text-white truncate">{{ session('company_data.video_link') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Terms Agreement --}}
            <div class="mt-8 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                               id="terms" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700"
                               required>
                    </div>
                    <div class="ml-3">
                        <label for="terms" class="text-sm font-medium text-gray-900 dark:text-white">
                            I confirm that all information provided is accurate and I am authorized to represent this company
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            By submitting, you agree to our Terms of Service and Privacy Policy. Your company will be pending verification.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('employer.company.branding') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Branding
                </a>
                <form method="POST" action="{{ route('employer.company.store.final') }}">
                    @csrf
                    <button type="submit" 
                            id="submit-btn"
                            disabled
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 opacity-50 cursor-not-allowed">
                        Create Company Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Note --}}
    <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-6">
        Your company will be pending verification. You'll receive an email once verified.
    </p>
</div>

<script>
document.getElementById('terms').addEventListener('change', function() {
    const submitBtn = document.getElementById('submit-btn');
    if (this.checked) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
});
</script>
@endsection