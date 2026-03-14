@extends('layouts.admin')

@section('title', $company->name . ' - Company Details - WorkNepal Admin')

@section('header', 'Company Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h2>
                @if($company->verification_status == 'verified')
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Verified</span>
                @elseif($company->verification_status == 'pending')
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>
                @elseif($company->verification_status == 'rejected')
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Rejected</span>
                @elseif($company->verification_status == 'suspended')
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm">Suspended</span>
                @endif
            </div>
            
            <div class="flex gap-3">
                @if($company->verification_status == 'pending')
                    <form method="POST" action="{{ route('admin.companies.verify', $company) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Verify Company
                        </button>
                    </form>
                    
                    <button type="button" 
                            @click="showRejectModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject
                    </button>
                @endif
                
                @if($company->verification_status == 'verified' || $company->verification_status == 'suspended')
                    <form method="POST" action="{{ route('admin.companies.suspend', $company) }}" 
                          onsubmit="return confirm('Suspend this company? This will also suspend all active jobs.')"
                          class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Suspend Company
                        </button>
                    </form>
                @endif
                
                @if($company->verification_status == 'suspended')
                    <form method="POST" action="{{ route('admin.companies.activate', $company) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Activate Company
                        </button>
                    </form>
                @endif
                
                <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" 
                      onsubmit="return confirm('Permanently delete this company? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Company
                    </button>
                </form>
            </div>
        </div>

        {{-- Company Overview Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Company Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Profile Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto mb-4">
                            @if($company->logo_path)
                                <img class="w-32 h-32 rounded-lg object-cover border-4 border-gray-200 dark:border-gray-700" 
                                     src="{{ Storage::url($company->logo_path) }}" alt="">
                            @else
                                <div class="w-32 h-32 rounded-lg bg-gradient-to-br from-gray-600 to-gray-500 flex items-center justify-center text-white text-4xl font-bold mx-auto border-4 border-gray-200 dark:border-gray-700">
                                    {{ substr($company->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member since {{ $company->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Industry</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->industry ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Location</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->location ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Size</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->size ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Founded</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->founded_year ?? 'N/A' }}</dd>
                            </div>
                            @if($company->website)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Website</dt>
                                    <dd class="text-sm font-medium">
                                        <a href="{{ $company->website }}" target="_blank" class="text-red-600 hover:text-red-700">
                                            {{ preg_replace('#^https?://#', '', $company->website) }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h4>
                    <dl class="space-y-3">
                        @if($company->contact_email)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <a href="mailto:{{ $company->contact_email }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600">
                                        {{ $company->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($company->phone)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <a href="tel:{{ $company->phone }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600">
                                        {{ $company->phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Owner Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Owner</h4>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($company->owner->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $company->owner->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $company->owner->email ?? '' }}</p>
                            <a href="{{ route('admin.users.show', $company->owner) }}" class="text-xs text-red-600 hover:text-red-700 mt-1 inline-block">
                                View Owner Profile →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Social Links --}}
                @if($company->social_links)
                    @php
                        $socialLinks = is_string($company->social_links) ? json_decode($company->social_links, true) : $company->social_links;
                    @endphp
                    @if(!empty($socialLinks))
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Social Media</h4>
                            <div class="flex gap-3">
                                @if($socialLinks['facebook'] ?? '')
                                    <a href="{{ $socialLinks['facebook'] }}" target="_blank" 
                                       class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($socialLinks['twitter'] ?? '')
                                    <a href="{{ $socialLinks['twitter'] }}" target="_blank" 
                                       class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.33-12.342c0-.213-.005-.425-.014-.636A10 10 0 0023.953 4.57z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($socialLinks['linkedin'] ?? '')
                                    <a href="{{ $socialLinks['linkedin'] }}" target="_blank" 
                                       class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-blue-800 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Culture Images --}}
                @if($company->culture_images)
                    @php
                        $cultureImages = is_string($company->culture_images) ? json_decode($company->culture_images, true) : $company->culture_images;
                    @endphp
                    @if(!empty($cultureImages))
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Culture Images</h4>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($cultureImages as $image)
                                    @if($image)
                                        <a href="{{ Storage::url($image) }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden hover:opacity-90 transition">
                                            <img src="{{ Storage::url($image) }}" alt="Culture" class="w-full h-full object-cover">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Right Column - Detailed Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_jobs'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Jobs</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_jobs'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Active Jobs</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_applications'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Applications</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['team_members'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Team Members</div>
                    </div>
                </div>

                {{-- Description Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About Company</h4>
                    <div class="prose max-w-none dark:prose-invert">
                        {!! nl2br(e($company->description ?? 'No description provided.')) !!}
                    </div>
                </div>

                {{-- Team Members --}}
                @if($company->teamMembers->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Team Members</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($company->teamMembers as $member)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-purple-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->pivot->role }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Recent Jobs --}}
                @if($recentJobs->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Job Postings</h4>
                        <div class="space-y-3">
                            @foreach($recentJobs as $job)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $job->title }}</p>
                                        <div class="flex gap-3 text-xs text-gray-500 mt-1">
                                            <span>{{ ucfirst($job->job_type) }}</span>
                                            <span>{{ $job->location }}</span>
                                            <span>{{ $job->applications_count ?? 0 }} applications</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($job->status == 'active') bg-green-100 text-green-800
                                        @elseif($job->status == 'closed') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.jobs.index', ['company' => $company->id]) }}" 
                               class="text-red-600 hover:text-red-700 text-sm font-medium">
                                View All Jobs →
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Company Video --}}
                @if($company->video_link)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Video</h4>
                        <div class="aspect-video rounded-lg overflow-hidden">
                            <iframe class="w-full h-full" src="{{ $company->video_link }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div x-data="{ showRejectModal: false }" 
     x-show="showRejectModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Company</h3>
            <form method="POST" action="{{ route('admin.companies.reject', $company) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Please provide a reason for rejecting this company..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showRejectModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection