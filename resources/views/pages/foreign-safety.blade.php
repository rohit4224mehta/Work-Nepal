@extends('layouts.app')

@section('title', 'Foreign Employment Safety Guide - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900">
    
    {{-- Hero with Warning --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-yellow-500 text-black rounded-full mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-semibold">⚠️ Important Safety Information</span>
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">Foreign Employment Safety Guide</h1>
            <p class="text-xl text-red-100 max-w-3xl mx-auto">
                Essential information to protect yourself when seeking employment abroad
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        {{-- Recent Alerts --}}
        @if(count($recentIncidents) > 0)
        <div class="mb-12 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-900 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-yellow-800 dark:text-yellow-400 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Recent Safety Alerts
            </h2>
            <div class="space-y-3">
                @foreach($recentIncidents as $incident)
                <div class="flex items-start">
                    <span class="inline-block w-16 text-sm font-medium text-yellow-700 dark:text-yellow-500">{{ $incident['country'] }}</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $incident['warning'] }}</span>
                    <span class="ml-auto text-sm text-gray-500">{{ $incident['date'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Warning Cards --}}
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            @foreach($warnings as $warning)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <div class="w-12 h-12 bg-{{ $warning['icon'] == 'exclamation-triangle' ? 'red' : ($warning['icon'] == 'check-circle' ? 'green' : 'blue') }}-100 dark:bg-{{ $warning['icon'] == 'exclamation-triangle' ? 'red' : ($warning['icon'] == 'check-circle' ? 'green' : 'blue') }}-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-{{ $warning['icon'] == 'exclamation-triangle' ? 'red' : ($warning['icon'] == 'check-circle' ? 'green' : 'blue') }}-600 dark:text-{{ $warning['icon'] == 'exclamation-triangle' ? 'red' : ($warning['icon'] == 'check-circle' ? 'green' : 'blue') }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($warning['icon'] == 'exclamation-triangle')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            @elseif($warning['icon'] == 'check-circle')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @elseif($warning['icon'] == 'document-duplicate')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @endif
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $warning['title'] }}</h2>
                    
                    @if(isset($warning['points']))
                    <ul class="space-y-3">
                        @foreach($warning['points'] as $point)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(isset($warning['steps']))
                    <ol class="space-y-3 list-decimal list-inside text-gray-600 dark:text-gray-400">
                        @foreach($warning['steps'] as $step)
                        <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                    @endif

                    @if(isset($warning['documents']))
                    <ul class="space-y-3">
                        @foreach($warning['documents'] as $doc)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">{{ $doc }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(isset($warning['countries']))
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($warning['countries'] as $country)
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="font-medium text-gray-900 dark:text-white">{{ explode(' - ', $country)[0] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ explode(' - ', $country)[1] ?? '' }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Government Resources --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Official Resources</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($resources as $resource)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $resource['name'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $resource['services'] }}</p>
                    <p class="text-sm text-gray-500 mb-2">📞 {{ $resource['phone'] }}</p>
                    <a href="{{ $resource['url'] }}" target="_blank" 
                       class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                        Visit Website
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FAQs --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Frequently Asked Questions</h2>
            <div class="space-y-4">
                @foreach($faqs as $faq)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            onclick="toggleFAQ(this)">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="hidden px-6 pb-4">
                        <p class="text-gray-600 dark:text-gray-400">{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="mt-12 p-8 bg-red-50 dark:bg-red-900/20 rounded-2xl text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Need Immediate Help?</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                If you're in an emergency situation or suspect fraud, contact our support team immediately.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="tel:+9771234567890" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Emergency Helpline
                </a>
                <a href="{{ route('pages.contact') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('svg');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endsection