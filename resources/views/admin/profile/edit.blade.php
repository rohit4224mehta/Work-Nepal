{{-- resources/views/admin/profile/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Profile - WorkNepal Admin')

@section('header', 'Edit Profile')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.profile.show') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Profile</h2>
        </div>

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Profile Photo --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Profile Photo
                    </label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-2 border-gray-200 dark:border-gray-600">
                                @if($admin->profile_photo_path)
                                    <img src="{{ $admin->profile_photo_url }}" alt="" class="w-full h-full object-cover" id="photo-preview">
                                @else
                                    <span class="text-2xl font-bold text-gray-500 dark:text-gray-400" id="photo-placeholder">
                                        {{ substr($admin->name, 0, 1) }}
                                    </span>
                                    <img src="" alt="" class="w-full h-full object-cover hidden" id="photo-preview">
                                @endif
                            </div>
                            @if($admin->profile_photo_path)
                                <button type="button" 
                                        onclick="removePhoto()"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition"
                                        title="Remove photo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <div>
                            <input type="file" 
                                   name="photo" 
                                   id="photo-input"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/20 dark:file:text-red-400">
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG • Max 2MB • Recommended 200x200</p>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name
                    </label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           value="{{ old('name', $admin->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email', $admin->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mobile --}}
                <div class="mb-4">
                    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Mobile Number
                    </label>
                    <input type="tel" 
                           id="mobile"
                           name="mobile" 
                           value="{{ old('mobile', $admin->mobile) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('mobile') border-red-500 @enderror"
                           placeholder="98XXXXXXXX">
                    @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date of Birth --}}
                <div class="mb-4">
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Date of Birth
                    </label>
                    <input type="date" 
                           id="date_of_birth"
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', $admin->date_of_birth ? $admin->date_of_birth->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-6">
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Gender
                    </label>
                    <select id="gender"
                            name="gender" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('gender') border-red-500 @enderror">
                        <option value="">Prefer not to say</option>
                        <option value="male" {{ old('gender', $admin->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $admin->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $admin->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.profile.show') }}" 
                       class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="remove-photo-form" method="POST" action="{{ route('admin.profile.remove-photo') }}" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Photo preview
    document.getElementById('photo-input')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photo-preview');
                const placeholder = document.getElementById('photo-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    });

    function removePhoto() {
        if (confirm('Remove your profile photo?')) {
            document.getElementById('remove-photo-form').submit();
        }
    }
</script>
@endpush
@endsection