@extends('layouts.app')

@section('title', isset($education) ? 'Edit Education' : 'Add Education - WorkNepal')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ isset($education) ? 'Edit Education' : 'Add Education' }}
            </h1>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ isset($education) ? route('education.update', $education->id) : route('education.store') }}">
                @csrf
                @if(isset($education))
                    @method('PUT')
                @endif
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Degree *</label>
                        <input type="text" name="degree" value="{{ old('degree', $education->degree ?? '') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        @error('degree')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Field of Study *</label>
                        <input type="text" name="field_of_study" value="{{ old('field_of_study', $education->field_of_study ?? '') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        @error('field_of_study')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Institution *</label>
                        <input type="text" name="institution" value="{{ old('institution', $education->institution ?? '') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        @error('institution')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $education->location ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                            <input type="date" name="start_date" value="{{ old('start_date', isset($education) ? $education->start_date->format('Y-m-d') : '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date', isset($education) && $education->end_date ? $education->end_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                   {{ isset($education) && $education->is_current ? 'disabled' : '' }}>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_current" value="1" 
                                   {{ old('is_current', isset($education) && $education->is_current) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700"
                                   onchange="toggleEndDate(this)">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I am currently studying here</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y">{{ old('description', $education->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('profile.edit') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        {{ isset($education) ? 'Update Education' : 'Save Education' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleEndDate(checkbox) {
    const endDateInput = document.querySelector('input[name="end_date"]');
    if (checkbox.checked) {
        endDateInput.disabled = true;
        endDateInput.value = '';
    } else {
        endDateInput.disabled = false;
    }
}
</script>
@endsection