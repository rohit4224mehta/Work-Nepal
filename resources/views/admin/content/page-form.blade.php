@extends('layouts.admin')

@section('title', isset($page) ? 'Edit Page: ' . $page->title : 'Create New Page - WorkNepal Admin')

@section('header', isset($page) ? 'Edit Page' : 'Create New Page')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.content.pages') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</h2>
        </div>

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ isset($page) ? route('admin.content.pages.update', $page) : route('admin.content.pages.store') }}" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @if(isset($page))
                    @method('PUT')
                @endif

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Page Title <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           id="title"
                           name="title" 
                           value="{{ old('title', $page->title ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug (Auto-generated) --}}
                @if(isset($page))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Slug
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500">{{ url('/') }}/</span>
                            <input type="text" 
                                   value="{{ $page->slug }}"
                                   disabled
                                   class="flex-1 px-4 py-2 bg-gray-100 border border-gray-300 dark:bg-gray-600 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300">
                        </div>
                    </div>
                @endif

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Content <span class="text-red-600">*</span>
                    </label>
                    <textarea id="content"
                              name="content" 
                              rows="15"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white font-mono">{{ old('content', $page->content ?? '') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meta Information --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">SEO & Meta Information</h3>
                    
                    {{-- Meta Title --}}
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Title
                        </label>
                        <input type="text" 
                               id="meta_title"
                               name="meta_title" 
                               value="{{ old('meta_title', $page->meta_title ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Description
                        </label>
                        <textarea id="meta_description"
                                  name="meta_description" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                    </div>

                    {{-- Meta Keywords --}}
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" 
                               id="meta_keywords"
                               name="meta_keywords" 
                               value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}"
                               placeholder="keyword1, keyword2, keyword3"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Template --}}
                    <div>
                        <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Template
                        </label>
                        <select id="template"
                                name="template" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                            @foreach($templates as $template)
                                <option value="{{ $template }}" {{ (old('template', $page->template ?? 'default') == $template) ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $template)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Featured Image --}}
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Featured Image
                        </label>
                        <input type="file" 
                               id="featured_image"
                               name="featured_image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        @if(isset($page) && $page->featured_image)
                            <div class="mt-2">
                                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="h-20 w-auto rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Publish Status --}}
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_published"
                           name="is_published" 
                           value="1"
                           {{ old('is_published', isset($page) ? $page->is_published : false) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_published" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Publish immediately
                    </label>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.content.pages') }}" 
                       class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        {{ isset($page) ? 'Update Page' : 'Create Page' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    // Initialize CKEditor for content editing
    CKEDITOR.replace('content', {
        height: 400,
        toolbar: [
            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
            { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
            { name: 'about', items: ['About'] }
        ]
    });
</script>
@endpush
@endsection