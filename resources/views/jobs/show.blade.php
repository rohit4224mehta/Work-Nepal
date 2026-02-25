@extends('layouts.guest')

@section('title', $job->title . ' – WorkNepal')

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-5xl">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
            ← Back to all jobs
        </a>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ $job->title }}</h1>
                    <p class="text-xl text-gray-700 mt-2">{{ $job->company }} • {{ $job->location }}</p>
                </div>
                @if($job->verified)
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">Verified Job</span>
                @endif
            </div>

            <div class="grid md:grid-cols-3 gap-6 mb-10 text-gray-700">
                <div>
                    <span class="block text-sm text-gray-500">Job Type</span>
                    <strong>{{ $job->type }}</strong>
                </div>
                <div>
                    <span class="block text-sm text-gray-500">Experience</span>
                    <strong>{{ $job->experience }}</strong>
                </div>
                <div>
                    <span class="block text-sm text-gray-500">Salary</span>
                    <strong>{{ $job->salary }}</strong>
                </div>
            </div>

            <div class="prose max-w-none">
                <h2 class="text-2xl font-bold mb-4">Job Description</h2>
                <p>{{ $job->description }}</p>

                <h2 class="text-2xl font-bold mt-10 mb-4">Requirements</h2>
                <ul class="list-disc pl-6 space-y-2">
                    @foreach(explode("\n", $job->requirements) as $req)
                        <li>{{ trim($req) }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-12 pt-8 border-t">
                <a href="{{ route('login') }}" class="block w-full md:w-auto text-center px-10 py-4 bg-blue-600 text-white font-bold rounded-lg text-lg hover:bg-blue-700 transition">
                    Apply Now (Login Required)
                </a>
            </div>
        </div>
    </div>
@endsection