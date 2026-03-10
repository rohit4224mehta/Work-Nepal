<div x-show="$store.mobileMenu.open" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-x-full"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-x-0"
     x-transition:leave-end="opacity-0 transform -translate-x-full"
     class="fixed inset-0 z-40 flex md:hidden">

    <!-- Overlay -->
    <div @click="$store.mobileMenu.close()" class="fixed inset-0 bg-black bg-opacity-50"></div>

    <!-- Menu Panel -->
    <div class="relative flex flex-col w-4/5 max-w-xs bg-white dark:bg-gray-900 shadow-xl">
        <div class="flex items-center justify-between px-4 py-5 border-b border-gray-200 dark:border-gray-800">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-600 rounded-md flex items-center justify-center text-white font-bold">
                    WN
                </div>
                <span class="text-xl font-bold text-gray-900 dark:text-white">WorkNepal</span>
            </a>
            <button @click="$store.mobileMenu.close()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
            <!-- Common Links -->
            <a href="{{ route('jobs.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                Jobs
            </a>
            <a href="#" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                Companies
            </a>
            <a href="{{ route('pages.cv-tips') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                CV Tips
            </a>

            @auth
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                    <!-- Role-specific -->
                    @role('job_seeker')
                        <a href="{{ route('saved.jobs') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Saved Jobs
                        </a>
                        <a href="{{ route('applications.index') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Applications
                        </a>
                    @endrole

                    @role('employer|recruiter')
                        <a href="{{ route('employer.post.job') ?? '#' }}" class="block text-lg font-medium text-red-600 hover:text-red-700 transition-colors py-2 font-semibold">
                            Post a Job
                        </a>
                        <a href="{{ route('employer.jobs.index') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            My Jobs
                        </a>
                        <a href="{{ route('employer.applicants') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Applicants
                        </a>
                    @endrole

                    <a href="{{ route('profile.edit') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        Profile Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left text-lg font-medium text-red-600 dark:text-red-400 hover:text-red-700 transition-colors py-2">
                            Log out
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('login') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="block text-center mt-4 py-3 px-6 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
                        Sign Up Free
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>