@extends('layouts.guest')

@section('title', 'Jobs in Nepal – WorkNepal')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-10">All Available Jobs</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($jobs as $job)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $job->title }}</h3>
                        <p class="text-gray-600 mb-1">{{ $job->company }}</p>
                        <p class="text-gray-500 text-sm mb-4">
                            {{ $job->location }} • {{ $job->type }} • {{ $job->posted }}
                        </p>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($job->verified)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Verified</span>
                            @endif
                            @if($job->fresher_friendly)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Fresher Friendly</span>
                            @endif
                        </div>

                        <a href="{{ route('jobs.show', 'demo-slug-'.$job->id) }}" 
                           class="block text-center py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination placeholder -->
        <div class="mt-12 flex justify-center">
            <div class="bg-gray-200 px-6 py-3 rounded-lg text-gray-600">
                Showing 1–12 of 5,200 jobs • Pagination coming soon
            </div>
        </div>
    </div>
@endsection