@extends('layouts.guest')

@section('title', 'Create Account - WorkNepal')

@section('content')
<div class="w-full max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-10 lg:px-12 lg:py-14">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Join WorkNepal Today
                </h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Find verified jobs, build your career — free & secure
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name
                    </label>
                    <input id="name" name="name" type="text" required autofocus
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3"
                           value="{{ old('name') }}">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address
                    </label>
                    <input id="email" name="email" type="email" required
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3"
                           value="{{ old('email') }}">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div class="mt-4">
    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Mobile Number
    </label>

    <div class="mt-1 flex rounded-lg shadow-sm">

        {{-- Country Code --}}
        <select name="country_code"
                class="rounded-l-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-3">

            <option value="+977" {{ old('country_code') == '+977' ? 'selected' : '' }}>
                🇳🇵 +977
            </option>

            <option value="+91" {{ old('country_code') == '+91' ? 'selected' : '' }}>
                🇮🇳 +91
            </option>

            <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>
                🇦🇪 +971
            </option>

            <option value="+974" {{ old('country_code') == '+974' ? 'selected' : '' }}>
                🇶🇦 +974
            </option>

        </select>

        {{-- Mobile Number --}}
        <input id="mobile"
               name="mobile"
               type="tel"
               inputmode="numeric"
               pattern="[0-9]{7,15}"
               placeholder="Enter mobile number"
               class="flex-1 rounded-r-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3"
               value="{{ old('mobile') }}"
               required>

    </div>

    @error('mobile')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                <!-- Gender – using enum cases dynamically -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Gender
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(\App\Support\Enums\Gender::cases() as $gender)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="gender" value="{{ $gender->value }}"
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                       {{ old('gender') == $gender->value ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $gender->label() }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date of Birth
                    </label>
                    <input id="date_of_birth" name="date_of_birth" type="date"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3"
                           value="{{ old('date_of_birth') }}">
                    @error('date_of_birth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-4 py-3">
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                           class="mt-1 h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="terms" class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                        I agree to the 
                        <a href="{{ route('pages.terms') }}" class="text-red-600 hover:underline" target="_blank">Terms of Service</a> and 
                        <a href="{{ route('pages.privacy') }}" class="text-red-600 hover:underline" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 px-6 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                    Create Free Account
                </button>

                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-red-600 hover:underline font-medium">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection