@extends('layouts.app')

@section('title', 'Create Company - Step 3: Branding - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Add Your Company Branding
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Make your company profile stand out with visual elements
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
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Basic Info</span>
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
            
            {{-- Step 3 Active --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Branding</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-gray-300 dark:bg-gray-700"></div>
            
            {{-- Step 4 Inactive --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full flex items-center justify-center font-bold">4</div>
                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">Review</span>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Form Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Step 3: Company Branding</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Upload visuals to make your profile attractive</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('employer.company.store.step3') }}" enctype="multipart/form-data" class="p-6 lg:p-8">
            @csrf
            
            <div class="space-y-8">
                {{-- Company Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Company Logo <span class="text-red-600">*</span>
                    </label>
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="w-32 h-32 rounded-xl bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden" id="logo-preview">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   name="logo" 
                                   id="logo-input"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="hidden"
                                   required>
                            <button type="button" 
                                    onclick="document.getElementById('logo-input').click()"
                                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Choose Logo
                            </button>
                            <p class="mt-2 text-sm text-gray-500">
                                JPG, PNG, WebP • Max 2MB • Recommended 200x200
                            </p>
                            @error('logo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Cover Image --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Cover Image
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center" id="cover-preview-container">
                        <input type="file" 
                               name="cover_image" 
                               id="cover-input"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="hidden">
                        <div id="cover-preview" class="mb-4 hidden">
                            <img src="" alt="Cover Preview" class="max-h-48 mx-auto rounded-lg">
                        </div>
                        <div id="cover-placeholder">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Upload a cover image for your company</p>
                            <p class="text-sm text-gray-500 mb-4">Recommended size: 1200 x 300 pixels</p>
                        </div>
                        <button type="button" 
                                onclick="document.getElementById('cover-input').click()"
                                class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Choose Cover Image
                        </button>
                    </div>
                </div>

                {{-- Culture Images --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Culture Photos (Optional)
                    </label>
                    <p class="text-sm text-gray-500 mb-3">Upload up to 3 photos showcasing your company culture</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center culture-upload" data-index="{{ $i }}">
                                <input type="file" 
                                       name="culture_image_{{ $i }}" 
                                       id="culture-input-{{ $i }}"
                                       accept="image/jpeg,image/png,image/jpg,image/webp"
                                       class="hidden">
                                <div class="culture-preview-{{ $i }} mb-2 hidden">
                                    <img src="" alt="Culture Image {{ $i }}" class="h-24 mx-auto rounded-lg">
                                </div>
                                <div class="culture-placeholder-{{ $i }}">
                                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-500">Photo {{ $i }}</p>
                                </div>
                                <button type="button" 
                                        onclick="document.getElementById('culture-input-{{ $i }}').click()"
                                        class="mt-2 text-xs text-red-600 hover:text-red-700">
                                    Choose Image
                                </button>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Video Link --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Video (YouTube/Vimeo Link)
                    </label>
                    <input type="url" 
                           name="video_link" 
                           value="{{ session('company_data.video_link') ?? old('video_link') }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500">Share a video about your company culture or work environment</p>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('employer.company.details') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Step 2
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Continue to Review
                        <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Logo preview
document.getElementById('logo-input')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
});

// Cover preview
document.getElementById('cover-input')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview').classList.remove('hidden');
            document.getElementById('cover-placeholder').classList.add('hidden');
            document.getElementById('cover-preview').innerHTML = `<img src="${e.target.result}" class="max-h-48 mx-auto rounded-lg">`;
        };
        reader.readAsDataURL(file);
    }
});

// Culture image previews
for (let i = 1; i <= 3; i++) {
    document.getElementById(`culture-input-${i}`)?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector(`.culture-preview-${i}`).classList.remove('hidden');
                document.querySelector(`.culture-placeholder-${i}`).classList.add('hidden');
                document.querySelector(`.culture-preview-${i} img`).src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}
</script>
@endsection