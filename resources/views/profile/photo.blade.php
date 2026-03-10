@extends('layouts.app')

@section('title', 'Change Profile Photo - WorkNepal')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Change Profile Photo
        </h1>
        <p class="mt-3 text-gray-600 dark:text-gray-400">
            A good profile photo helps employers recognize you quickly.
        </p>
    </div>

    <!-- Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 lg:p-10">

            <!-- Current Photo -->
            <div class="flex flex-col items-center mb-10">
                <div class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 shadow-lg">
                    <img id="preview" 
                         src="{{ auth()->user()->profilePhotoUrl ?? asset('images/default-avatar.png') }}" 
                         alt="Current Profile Photo" 
                         class="w-full h-full object-cover">

                    <!-- Overlay for upload -->
                    <label for="photo" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm font-medium">Change Photo</span>
                        </div>
                    </label>
                </div>

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Recommended: square photo, at least 400x400 pixels
                </p>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PATCH')

                <!-- File Input -->
                <div class="flex flex-col items-center">
                    <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Upload new photo (JPG, PNG, max 2MB)
                    </label>

                    <div class="relative w-full max-w-xs">
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" 
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 focus:outline-none focus:border-red-500 dark:focus:border-red-500 file:mr-4 file:py-3 file:px-6 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/30 dark:file:text-red-300">

                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview + Remove -->
                <div class="flex flex-col items-center gap-6">
                    <!-- Remove Current Photo -->
                    @if(auth()->user()->profile_photo_path)
                        <form method="POST" action="{{ route('profile.photo.remove') }}" class="w-full max-w-xs">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-3 px-6 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors">
                                Remove Current Photo
                            </button>
                        </form>
                    @endif

                    <!-- Save Button -->
                    <button type="submit" class="w-full max-w-xs py-3 px-6 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Save Photo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-8 text-center">
        <a href="{{ route('profile.show', auth()->user()) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors">
            ← Back to Profile
        </a>
    </div>

</div>

<!-- JavaScript for Real-time Preview -->
<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection