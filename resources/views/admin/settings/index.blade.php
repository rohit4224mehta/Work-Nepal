@extends('layouts.admin')

@section('title', 'System Settings - WorkNepal Admin')

@section('header', 'System Settings')

@section('content')
<div class="py-6" x-data="settingsManagement()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">System Configuration</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage platform settings and rules
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <button @click="clearCache()" 
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Clear Cache
                </button>
                <button @click="exportSettings()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <button @click="showImportModal = true" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    Import
                </button>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex flex-wrap">
                @foreach($groups as $groupKey => $groupName)
                    <a href="{{ route('admin.settings.index', ['group' => $groupKey]) }}" 
                       class="mr-6 py-3 px-1 text-sm font-medium border-b-2 transition
                              {{ $group == $groupKey ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        {{ $groupName }}
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Settings Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 space-y-6">
                    @foreach($settings as $setting)
                        <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex-1">
                                <label for="{{ $setting->key }}" class="block text-sm font-medium text-gray-900 dark:text-white mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                </label>
                                
                                @if($setting->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $setting->description }}</p>
                                @endif

                                @switch($setting->type)
                                    @case('boolean')
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="{{ $setting->key }}"
                                                   name="{{ $setting->key }}" 
                                                   value="1"
                                                   {{ $setting->typed_value ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enabled</span>
                                        </div>
                                        @break

                                    @case('textarea')
                                        <textarea id="{{ $setting->key }}"
                                                  name="{{ $setting->key }}" 
                                                  rows="4"
                                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">{{ $setting->value }}</textarea>
                                        @break

                                    @case('select')
                                        <select id="{{ $setting->key }}"
                                                name="{{ $setting->key }}" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                            @foreach(json_decode($setting->options, true) as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" {{ $setting->value == $optionValue ? 'selected' : '' }}>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case('multiselect')
                                        @php $selectedValues = explode(',', $setting->value); @endphp
                                        <select id="{{ $setting->key }}"
                                                name="{{ $setting->key }}[]" 
                                                multiple
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                            @foreach(json_decode($setting->options, true) as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" {{ in_array($optionValue, $selectedValues) ? 'selected' : '' }}>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple</p>
                                        @break

                                    @case('file')
                                        <div class="flex items-center gap-3">
                                            <input type="file" 
                                                   id="{{ $setting->key }}"
                                                   name="{{ $setting->key }}" 
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/20 dark:file:text-red-400">
                                            @if($setting->value)
                                                <span class="text-xs text-gray-500">Current: {{ $setting->value }}</span>
                                            @endif
                                        </div>
                                        @break

                                    @case('json')
                                        @php $jsonValue = json_decode($setting->value, true); @endphp
                                        @if(is_array($jsonValue))
                                            <div class="space-y-2">
                                                @foreach($jsonValue as $index => $item)
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" 
                                                               name="{{ $setting->key }}[]" 
                                                               value="{{ $item }}"
                                                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                                        <button type="button" 
                                                                @click="removeJsonItem($event)"
                                                                class="p-2 text-red-600 hover:text-red-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                                <button type="button" 
                                                        @click="addJsonItem('{{ $setting->key }}')"
                                                        class="mt-2 inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Add Item
                                                </button>
                                            </div>
                                        @endif
                                        @break

                                    @default
                                        <input type="{{ $setting->type == 'number' ? 'number' : 'text' }}" 
                                               id="{{ $setting->key }}"
                                               name="{{ $setting->key }}" 
                                               value="{{ $setting->value }}"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Save Settings
                    </button>
                    
                    <button type="button" 
                            @click="resetDefaults('{{ $group }}')"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Reset to Defaults
                    </button>
                </div>
            </form>
        </div>

        {{-- Environment Information --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Environment Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">PHP Version</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $envInfo['php_version'] }}</p>
                </div>
                
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Laravel Version</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $envInfo['laravel_version'] }}</p>
                </div>
                
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Environment</span>
                    <p class="text-sm font-medium">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($envInfo['environment'] == 'production') bg-red-100 text-red-800
                            @elseif($envInfo['environment'] == 'staging') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($envInfo['environment']) }}
                        </span>
                    </p>
                </div>
                
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Debug Mode</span>
                    <p class="text-sm font-medium">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $envInfo['debug_mode'] ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $envInfo['debug_mode'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </p>
                </div>
                
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Cache Driver</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($envInfo['cache_driver']) }}</p>
                </div>
                
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Session Driver</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($envInfo['session_driver']) }}</p>
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                <form method="POST" action="{{ route('admin.settings.clear-cache') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                        Clear All Cache
                    </button>
                </form>
                
                <button @click="showEnvModal = true" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Edit Environment
                </button>
            </div>
        </div>

        {{-- Test Email Card --}}
        @if($group == Setting::GROUP_EMAIL)
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Email Configuration</h3>
                
                <form method="POST" action="{{ route('admin.settings.test-email') }}" class="flex gap-3">
                    @csrf
                    <input type="email" 
                           name="test_email" 
                           placeholder="Enter email address to test"
                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                           required>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Send Test Email
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- Import Modal --}}
<div x-data="{ showImportModal: false }" 
     x-show="showImportModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Import Settings</h3>
            
            <form method="POST" action="{{ route('admin.settings.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Settings File (JSON)
                    </label>
                    <input type="file" 
                           name="settings_file" 
                           accept=".json"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Upload a JSON file exported from settings</p>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showImportModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Import Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Environment Modal --}}
<div x-data="{ showEnvModal: false, appName: '{{ config('app.name') }}', appEnv: '{{ config('app.env') }}', appDebug: {{ config('app.debug') ? 'true' : 'false' }}, appUrl: '{{ config('app.url') }}' }" 
     x-show="showEnvModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Environment Configuration</h3>
            
            <form method="POST" action="{{ route('admin.settings.update-env') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Application Name
                        </label>
                        <input type="text" 
                               x-model="appName"
                               name="app_name" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Environment
                        </label>
                        <select name="app_env" 
                                x-model="appEnv"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                            <option value="local">Local</option>
                            <option value="staging">Staging</option>
                            <option value="production">Production</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Debug Mode
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="app_debug" 
                                   x-model="appDebug"
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable debug mode</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Application URL
                        </label>
                        <input type="url" 
                               x-model="appUrl"
                               name="app_url" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               required>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" 
                            @click="showEnvModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Update Environment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function settingsManagement() {
    return {
        init() {
            // Initialize any necessary data
        },
        
        addJsonItem(key) {
            const container = event.target.closest('.space-y-2');
            const newInput = document.createElement('div');
            newInput.className = 'flex items-center gap-2';
            newInput.innerHTML = `
                <input type="text" 
                       name="${key}[]" 
                       value=""
                       class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                <button type="button" 
                        onclick="this.closest('.flex').remove()"
                        class="p-2 text-red-600 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newInput);
        },
        
        removeJsonItem(event) {
            event.target.closest('.flex').remove();
        },
        
        clearCache() {
            if (confirm('Clear all application cache? This may temporarily affect performance.')) {
                fetch('{{ route("admin.settings.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    location.reload();
                });
            }
        },
        
        exportSettings() {
            const group = '{{ $group }}';
            window.location.href = '{{ route("admin.settings.export") }}?group=' + group;
        },
        
        resetDefaults(group) {
            if (confirm('Reset all settings in this group to default values? This action cannot be undone.')) {
                window.location.href = '{{ route("admin.settings.reset") }}?group=' + group;
            }
        }
    }
}
</script>
@endpush
@endsection