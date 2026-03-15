<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingController extends AdminController
{
    /**
     * Display settings page.
     */
    public function index(Request $request): View
    {
        $group = $request->get('group', Setting::GROUP_GENERAL);
        
        $settings = Setting::where('group', $group)
            ->orderBy('key')
            ->get();

        $groups = [
            Setting::GROUP_GENERAL => 'General',
            Setting::GROUP_JOB => 'Job Settings',
            Setting::GROUP_APPLICATION => 'Application Settings',
            Setting::GROUP_USER => 'User Settings',
            Setting::GROUP_COMPANY => 'Company Settings',
            Setting::GROUP_EMAIL => 'Email Configuration',
            Setting::GROUP_NOTIFICATION => 'Notifications',
            Setting::GROUP_SECURITY => 'Security',
            Setting::GROUP_PAYMENT => 'Payment Settings',
            Setting::GROUP_API => 'API Settings',
        ];

        // Get environment info
        $envInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.settings.index', compact('settings', 'groups', 'group', 'envInfo'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Handle different field types
                if ($setting->type === Setting::TYPE_BOOLEAN) {
                    $value = $value ? '1' : '0';
                } elseif ($setting->type === Setting::TYPE_JSON && is_array($value)) {
                    $value = json_encode($value);
                } elseif ($setting->type === Setting::TYPE_MULTISELECT && is_array($value)) {
                    $value = implode(',', $value);
                }

                $setting->update(['value' => $value]);
            }
        }

        // Clear settings cache
        Setting::clearCache();

        $this->logAdminAction(
            'settings_updated',
            "Updated system settings"
        );

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Update environment configuration.
     */
    public function updateEnv(Request $request): RedirectResponse
    {
        $request->validate([
            'app_name' => 'required|string',
            'app_env' => 'required|in:local,production,staging',
            'app_debug' => 'required|boolean',
            'app_url' => 'required|url',
        ]);

        try {
            $this->updateEnvironmentFile([
                'APP_NAME' => '"' . $request->app_name . '"',
                'APP_ENV' => $request->app_env,
                'APP_DEBUG' => $request->app_debug ? 'true' : 'false',
                'APP_URL' => $request->app_url,
            ]);

            $this->logAdminAction(
                'env_updated',
                "Updated environment configuration"
            );

            return back()->with('success', 'Environment configuration updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update environment configuration.');
        }
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Send test email
            \Mail::raw('This is a test email from WorkNepal. Your email configuration is working correctly!', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email from WorkNepal');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            $this->logAdminAction(
                'cache_cleared',
                "Cleared application cache"
            );

            return back()->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache.');
        }
    }

    /**
     * Reset settings to default.
     */
    public function resetDefaults(Request $request): RedirectResponse
    {
        $group = $request->get('group', Setting::GROUP_GENERAL);

        try {
            // Run seeder for the specific group
            Artisan::call('db:seed', [
                '--class' => 'SettingsSeeder',
                '--force' => true,
            ]);

            Setting::clearCache();

            $this->logAdminAction(
                'settings_reset',
                "Reset settings for group: {$group}"
            );

            return back()->with('success', 'Settings reset to default values successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reset settings.');
        }
    }

    /**
     * Export settings.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $group = $request->get('group', Setting::GROUP_GENERAL);
        
        $settings = Setting::where('group', $group)->get();

        $filename = 'settings_' . $group . '_' . date('Y-m-d_His') . '.json';
        $path = storage_path('app/' . $filename);

        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT));

        $this->logAdminAction(
            'settings_exported',
            "Exported settings for group: {$group}"
        );

        return response()->download($path)->deleteFileAfterSend(true);
    }

    /**
     * Import settings.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json|max:2048',
        ]);

        try {
            $file = $request->file('settings_file');
            $content = file_get_contents($file->path());
            $importedSettings = json_decode($content, true);

            foreach ($importedSettings as $imported) {
                $setting = Setting::where('key', $imported['key'])->first();
                
                if ($setting) {
                    $setting->update(['value' => $imported['value']]);
                }
            }

            Setting::clearCache();

            $this->logAdminAction(
                'settings_imported',
                "Imported settings from file: " . $file->getClientOriginalName()
            );

            return back()->with('success', 'Settings imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import settings.');
        }
    }

    /**
     * Update environment file.
     */
    private function updateEnvironmentFile(array $data): void
    {
        $envFile = base_path('.env');
        $content = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $content
            );
        }

        file_put_contents($envFile, $content);
    }

    /**
     * Get setting value helper for use in other controllers.
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}