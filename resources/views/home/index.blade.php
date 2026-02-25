@extends('layouts.guest')

@section('title', 'WorkNepal – Nepal Job Search & Hiring Platform')

@section('content')

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white py-24 md:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[url('/images/nepal-pattern-light.svg')] bg-repeat"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                Find Your Dream Job in Nepal
            </h1>
            
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto font-light">
                Verified jobs • Fresher friendly • Foreign employment with safety info • Mobile first
            </p>

            <!-- Search Bar -->
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl p-4 md:p-6">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Job title, skills, company name..." 
                        class="flex-1 px-6 py-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 text-lg"
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors text-lg whitespace-nowrap"
                    >
                        Search Jobs
                    </button>
                </form>
            </div>

            <div class="mt-10 flex flex-wrap justify-center gap-6 text-lg">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-700 rounded-lg font-bold hover:bg-gray-100 transition">
                    Create Free Account
                </a>
                <a href="{{ route('jobs.index') }}" class="px-8 py-4 border-2 border-white text-white rounded-lg font-bold hover:bg-white hover:text-blue-700 transition">
                    Browse All Jobs
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                <div class="p-8 bg-gray-50 rounded-xl shadow-sm">
                    <div class="text-5xl font-bold text-blue-600 mb-3">{{ number_format($stats['jobs_count']) }}+</div>
                    <p class="text-xl text-gray-700">Active Job Listings</p>
                </div>
                <div class="p-8 bg-gray-50 rounded-xl shadow-sm">
                    <div class="text-5xl font-bold text-blue-600 mb-3">{{ number_format($stats['companies_count']) }}+</div>
                    <p class="text-xl text-gray-700">Registered Companies</p>
                </div>
                <div class="p-8 bg-gray-50 rounded-xl shadow-sm">
                    <div class="text-5xl font-bold text-blue-600 mb-3">{{ number_format($stats['freshers_hired']) }}+</div>
                    <p class="text-xl text-gray-700">Freshers Placed</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16">Why Choose WorkNepal?</h2>
            
            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                    <div class="text-blue-600 text-5xl mb-6">✓</div>
                    <h3 class="text-2xl font-bold mb-4">Verified Listings</h3>
                    <p class="text-gray-600">Every job is reviewed by our team to reduce fake & misleading postings.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                    <div class="text-blue-600 text-5xl mb-6">🎓</div>
                    <h3 class="text-2xl font-bold mb-4">Fresher Friendly</h3>
                    <p class="text-gray-600">Special filters & badges for entry-level jobs & internships.</p>
                </div>
                
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                    <div class="text-blue-600 text-5xl mb-6">🌍</div>
                    <h3 class="text-2xl font-bold mb-4">Safe Foreign Jobs</h3>
                    <p class="text-gray-600">Government guidelines & safety information for overseas opportunities.</p>
                </div>
            </div>
        </div>
    </section>

@endsection