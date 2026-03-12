@extends('layouts.app')

@section('title', 'Privacy Policy - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900">
    
    {{-- Hero --}}
    <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Privacy Policy</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Last Updated: {{ $lastUpdated }}
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Introduction --}}
        <div class="prose prose-lg dark:prose-invert max-w-none mb-12">
            <p class="text-gray-600 dark:text-gray-400">
                At WorkNepal, we take your privacy seriously. This policy describes how we collect, use, and protect your personal information when you use our platform. We comply with Nepal's data protection laws and international privacy standards.
            </p>
        </div>

        {{-- Sections --}}
        <div class="space-y-12">
            @foreach($sections as $section)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $section['title'] }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $section['content'] }}</p>
                
                @if(isset($section['subpoints']))
                <ul class="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-400">
                    @foreach($section['subpoints'] as $point)
                    <li>{{ $point }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Contact for Privacy --}}
        <div class="mt-12 p-8 bg-red-50 dark:bg-red-900/20 rounded-2xl">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Questions About Your Privacy?</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                If you have any questions about how we handle your data or want to exercise your privacy rights, please contact our Data Protection Officer.
            </p>
            <a href="{{ route('pages.contact') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Contact Privacy Team
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection