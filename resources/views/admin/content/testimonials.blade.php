@extends('layouts.admin')

@section('title', 'Testimonials Moderation - WorkNepal Admin')

@section('header', 'Testimonials Moderation')

@section('content')
<div class="py-6" x-data="testimonialsManagement()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Moderate user testimonials and success stories
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <button @click="exportTestimonials()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <button @click="refreshStats()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Total</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Pending</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Approved</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Rejected</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['featured'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Featured</div>
                <div class="text-xs text-gray-500">Avg Rating: {{ number_format($stats['avg_rating'], 1) }}/5</div>
            </div>
        </div>

        {{-- Bulk Actions Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-4 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="select-all" 
                           @click="toggleSelectAll"
                           :checked="selectedTestimonials.length === totalTestimonials && totalTestimonials > 0"
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                    <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</label>
                </div>
                
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span x-text="selectedTestimonials.length" class="font-semibold"></span> testimonials selected
                </span>

                <div class="flex-1"></div>

                <select x-model="bulkAction" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Bulk Actions</option>
                    <option value="approve">Approve Selected</option>
                    <option value="reject">Reject Selected</option>
                    <option value="feature">Feature Selected</option>
                    <option value="unfeature">Unfeature Selected</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <button @click="applyBulkAction" 
                        :disabled="!bulkAction || selectedTestimonials.length === 0"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Apply
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.content.testimonials.index') }}" class="space-y-4">
                    <div class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by content or user..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-40">
                            <select name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Featured</option>
                            </select>
                        </div>

                        {{-- Rating Filter --}}
                        <div class="w-40">
                            <select name="rating" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div class="w-40">
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   placeholder="From Date"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="w-40">
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   placeholder="To Date"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Sort --}}
                        <div class="w-40">
                            <select name="sort" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Highest Rating</option>
                                <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Lowest Rating</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.content.testimonials.index') }}" 
                               class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($testimonials as $testimonial)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($testimonial->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $testimonial->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-xs text-gray-500">{{ $testimonial->user->email ?? '' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-1">
                                @if($testimonial->featured)
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                        Featured
                                    </span>
                                @endif
                                
                                @if($testimonial->is_approved)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Approved
                                    </span>
                                @elseif($testimonial->rejection_reason)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Rating --}}
                        @if($testimonial->rating)
                            <div class="flex mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        @endif

                        {{-- Content --}}
                        <p class="text-gray-700 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            "{{ $testimonial->content }}"
                        </p>

                        {{-- Job Details --}}
                        @if($testimonial->job_title || $testimonial->company_name)
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                @if($testimonial->job_title)
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-medium">Position:</span> {{ $testimonial->job_title }}
                                    </p>
                                @endif
                                @if($testimonial->company_name)
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-medium">Company:</span> {{ $testimonial->company_name }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Rejection Reason --}}
                        @if($testimonial->rejection_reason)
                            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <p class="text-xs text-red-700 dark:text-red-400">
                                    <span class="font-medium">Rejection reason:</span><br>
                                    {{ $testimonial->rejection_reason }}
                                </p>
                            </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="text-xs text-gray-500 mb-4">
                            <div>Created: {{ $testimonial->created_at->format('M d, Y') }}</div>
                            @if($testimonial->moderated_at)
                                <div>Moderated: {{ $testimonial->moderated_at->format('M d, Y') }} by {{ $testimonial->moderator->name ?? 'Admin' }}</div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-2">
                            @if(!$testimonial->is_approved && !$testimonial->rejection_reason)
                                <form method="POST" action="{{ route('admin.content.testimonials.approve', $testimonial) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs font-medium">
                                        Approve
                                    </button>
                                </form>
                                
                                <button type="button" 
                                        @click="rejectTestimonial({{ $testimonial->id }}, '{{ addslashes($testimonial->content) }}')"
                                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs font-medium">
                                    Reject
                                </button>
                            @endif

                            <form method="POST" action="{{ route('admin.content.testimonials.toggle-featured', $testimonial) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1 {{ $testimonial->featured ? 'bg-yellow-600' : 'bg-purple-600' }} text-white rounded-lg hover:opacity-90 transition text-xs font-medium">
                                    {{ $testimonial->featured ? 'Unfeature' : 'Feature' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.content.testimonials.delete', $testimonial) }}" 
                                  onsubmit="return confirm('Delete this testimonial? This action cannot be undone.')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-xs font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No testimonials found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search filters</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($testimonials->hasPages())
            <div class="mt-6">
                {{ $testimonials->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Bulk Action Form --}}
<form id="bulk-action-form" method="POST" action="{{ route('admin.content.testimonials.bulk') }}" class="hidden">
    @csrf
</form>

{{-- Reject Modal --}}
<div x-data="{ showRejectModal: false, testimonialId: null, testimonialContent: '' }" 
     x-show="showRejectModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Testimonial</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4" x-text="'Reject: ' + testimonialContent.substring(0, 50) + '...'"></p>
            <form method="POST" :action="`/admin/content/testimonials/${testimonialId}/reject`">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Please provide a reason for rejecting this testimonial..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showRejectModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testimonialsManagement() {
    return {
        selectedTestimonials: [],
        bulkAction: '',
        totalTestimonials: {{ $testimonials->total() }},
        
        toggleSelectAll() {
            if (this.selectedTestimonials.length === this.totalTestimonials) {
                this.selectedTestimonials = [];
            } else {
                this.selectedTestimonials = @json($testimonials->pluck('id'));
            }
        },
        
        applyBulkAction() {
            if (!this.bulkAction || this.selectedTestimonials.length === 0) return;
            
            let confirmMessage = '';
            switch (this.bulkAction) {
                case 'approve':
                    confirmMessage = `Approve ${this.selectedTestimonials.length} testimonial(s)?`;
                    break;
                case 'reject':
                    confirmMessage = `Reject ${this.selectedTestimonials.length} testimonial(s)?`;
                    break;
                case 'feature':
                    confirmMessage = `Feature ${this.selectedTestimonials.length} testimonial(s)?`;
                    break;
                case 'unfeature':
                    confirmMessage = `Unfeature ${this.selectedTestimonials.length} testimonial(s)?`;
                    break;
                case 'delete':
                    confirmMessage = `Delete ${this.selectedTestimonials.length} testimonial(s)? This action cannot be undone.`;
                    break;
            }
            
            if (!confirm(confirmMessage)) return;
            
            const form = document.getElementById('bulk-action-form');
            form.innerHTML = '';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = this.bulkAction;
            form.appendChild(actionInput);
            
            this.selectedTestimonials.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'testimonial_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            form.submit();
        },
        
        exportTestimonials() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '{{ route("admin.content.testimonials.export") }}?' + params.toString();
        },
        
        rejectTestimonial(id, content) {
            this.testimonialId = id;
            this.testimonialContent = content;
            this.showRejectModal = true;
        },
        
        refreshStats() {
            fetch('{{ route("admin.content.stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update stats display
                    location.reload();
                });
        }
    }
}
</script>
@endpush
@endsection