<footer class="bg-gray-900 text-gray-300 pt-12 pb-8">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Brand & About -->
            <div class="space-y-6">
                <h4 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                    </svg>
                    WorkNepal
                </h4>

                <p class="text-gray-400 leading-relaxed">
                    Nepal's trusted job search platform — connecting job seekers with verified employers.  
                    Real opportunities in Nepal and abroad, with transparency and safety first.
                </p>

                <div class="flex gap-5">
                    <a href="#" aria-label="Facebook" class="text-gray-400 hover:text-red-600 transition-colors text-xl">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Twitter / X" class="text-gray-400 hover:text-red-600 transition-colors text-xl">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="text-gray-400 hover:text-red-600 transition-colors text-xl">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="text-gray-400 hover:text-red-600 transition-colors text-xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h6 class="text-lg font-semibold text-white mb-5">Quick Links</h6>
                <ul class="space-y-3">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-red-600 transition-colors">Find Jobs</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Post a Job</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="hover:text-red-600 transition-colors">My Profile</a></li>
                    {{-- <li><a href="{{ route('cv-tips') }}" class="hover:text-red-600 transition-colors">Career Tips</a></li> --}}
                </ul>
            </div>

            <!-- Support & Legal -->
            <div>
                <h6 class="text-lg font-semibold text-white mb-5">Support</h6>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Help Center</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-red-600 transition-colors">Contact Us</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="hover:text-red-600 transition-colors">Terms of Service</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="hover:text-red-600 transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.foreign-safety') }}" class="hover:text-red-600 transition-colors">Foreign Employment Guidelines</a></li>
                </ul>
            </div>

            <!-- Contact & Newsletter -->
            <div class="space-y-6">
                <div>
                    <h6 class="text-lg font-semibold text-white mb-5">Contact Us</h6>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span>support@worknepal.com</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <span>+977 980-1234567</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Kathmandu, Nepal</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter (placeholder – later use Laravel action) -->
                <div>
                    <h6 class="text-lg font-semibold text-white mb-3">Newsletter</h6>
                    <form class="flex flex-col sm:flex-row gap-2">
                        <input 
                            type="email" 
                            class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600"
                            placeholder="Your email address"
                            aria-label="Email for newsletter"
                        >
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors whitespace-nowrap"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <div class="mb-4 md:mb-0">
                    © {{ date('Y') }} WorkNepal — All rights reserved.
                </div>
                <div class="flex items-center gap-2">
                    Made with 
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg> 
                    in Nepal
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Optional: Custom hover class if you want to keep it -->
<style>
    .hover\:text-red-600:hover {
        color: #DC2626 !important;
    }
</style>