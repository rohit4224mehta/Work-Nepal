<?php
// database/seeders/SettingsSeeder.php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'WorkNepal',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'The name of the website',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'Nepal\'s #1 Job Search & Hiring Platform',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Site meta description',
                'is_public' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@worknepal.com',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Primary contact email address',
                'is_public' => true,
            ],
            [
                'key' => 'support_phone',
                'value' => '+977 1234567890',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Customer support phone number',
                'is_public' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Kathmandu',
                'type' => Setting::TYPE_SELECT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Default timezone for the application',
                'options' => json_encode([
                    'Asia/Kathmandu' => 'Kathmandu (UTC+5:45)',
                    'Asia/Kolkata' => 'Kolkata (UTC+5:30)',
                ]),
                'is_public' => false,
            ],
            [
                'key' => 'date_format',
                'value' => 'M d, Y',
                'type' => Setting::TYPE_SELECT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Date format throughout the site',
                'options' => json_encode([
                    'Y-m-d' => 'YYYY-MM-DD',
                    'd/m/Y' => 'DD/MM/YYYY',
                    'm/d/Y' => 'MM/DD/YYYY',
                    'M d, Y' => 'Month DD, YYYY',
                ]),
                'is_public' => false,
            ],

            // Job Settings
            [
                'key' => 'job_expiry_days',
                'value' => '30',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_JOB,
                'description' => 'Number of days before a job posting expires',
                'is_public' => false,
            ],
            [
                'key' => 'max_active_jobs_per_company',
                'value' => '20',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_JOB,
                'description' => 'Maximum number of active jobs a company can have',
                'is_public' => false,
            ],
            [
                'key' => 'require_job_approval',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_JOB,
                'description' => 'Require admin approval for new job postings',
                'is_public' => false,
            ],
            [
                'key' => 'allow_featured_jobs',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_JOB,
                'description' => 'Allow employers to feature their jobs',
                'is_public' => false,
            ],
            [
                'key' => 'featured_job_price',
                'value' => '1000',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_JOB,
                'description' => 'Price for featured job (in NPR)',
                'is_public' => false,
            ],
            [
                'key' => 'job_categories',
                'value' => json_encode([
                    'IT & Software',
                    'Marketing',
                    'Finance',
                    'Sales',
                    'Design',
                    'HR',
                    'Engineering',
                    'Healthcare',
                    'Education',
                    'Hospitality',
                ]),
                'type' => Setting::TYPE_JSON,
                'group' => Setting::GROUP_JOB,
                'description' => 'Available job categories',
                'is_public' => true,
            ],
            [
                'key' => 'job_types',
                'value' => json_encode([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'remote' => 'Remote',
                    'freelance' => 'Freelance',
                ]),
                'type' => Setting::TYPE_JSON,
                'group' => Setting::GROUP_JOB,
                'description' => 'Available job types',
                'is_public' => true,
            ],

            // Application Settings
            [
                'key' => 'max_applications_per_job',
                'value' => '500',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_APPLICATION,
                'description' => 'Maximum number of applications allowed per job',
                'is_public' => false,
            ],
            [
                'key' => 'allow_multiple_applications',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_APPLICATION,
                'description' => 'Allow users to apply to multiple jobs',
                'is_public' => false,
            ],
            [
                'key' => 'require_resume_for_application',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_APPLICATION,
                'description' => 'Require users to upload resume before applying',
                'is_public' => false,
            ],
            [
                'key' => 'application_statuses',
                'value' => json_encode([
                    'applied',
                    'viewed',
                    'shortlisted',
                    'rejected',
                    'hired',
                ]),
                'type' => Setting::TYPE_JSON,
                'group' => Setting::GROUP_APPLICATION,
                'description' => 'Available application statuses',
                'is_public' => true,
            ],

            // User Settings
            [
                'key' => 'allow_registration',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_USER,
                'description' => 'Allow new user registration',
                'is_public' => false,
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_USER,
                'description' => 'Require email verification for new accounts',
                'is_public' => false,
            ],
            [
                'key' => 'require_mobile_verification',
                'value' => '0',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_USER,
                'description' => 'Require mobile verification for new accounts',
                'is_public' => false,
            ],
            [
                'key' => 'min_password_length',
                'value' => '8',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_USER,
                'description' => 'Minimum password length',
                'is_public' => false,
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_SECURITY,
                'description' => 'Maximum login attempts before lockout',
                'is_public' => false,
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_SECURITY,
                'description' => 'Lockout duration in minutes after failed attempts',
                'is_public' => false,
            ],

            // Company Settings
            [
                'key' => 'require_company_verification',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_COMPANY,
                'description' => 'Require admin verification for new companies',
                'is_public' => false,
            ],
            [
                'key' => 'max_team_members_per_company',
                'value' => '10',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_COMPANY,
                'description' => 'Maximum number of team members per company',
                'is_public' => false,
            ],

            // File Upload Settings
            [
                'key' => 'max_file_upload_size',
                'value' => '5120',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Maximum file upload size in KB (5MB = 5120KB)',
                'is_public' => false,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => json_encode(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']),
                'type' => Setting::TYPE_JSON,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Allowed file types for upload',
                'is_public' => false,
            ],
            [
                'key' => 'max_resume_size',
                'value' => '2048',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_USER,
                'description' => 'Maximum resume file size in KB (2MB = 2048KB)',
                'is_public' => false,
            ],
            [
                'key' => 'allowed_resume_types',
                'value' => json_encode(['pdf', 'doc', 'docx']),
                'type' => Setting::TYPE_JSON,
                'group' => Setting::GROUP_USER,
                'description' => 'Allowed resume file types',
                'is_public' => false,
            ],

            // Email Settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => Setting::TYPE_SELECT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'Email driver',
                'options' => json_encode([
                    'smtp' => 'SMTP',
                    'sendmail' => 'Sendmail',
                    'mailgun' => 'Mailgun',
                    'ses' => 'Amazon SES',
                ]),
                'is_public' => false,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'SMTP host',
                'is_public' => false,
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'SMTP port',
                'is_public' => false,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'SMTP username',
                'is_public' => false,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'SMTP password',
                'is_public' => false,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => Setting::TYPE_SELECT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'SMTP encryption',
                'options' => json_encode([
                    'tls' => 'TLS',
                    'ssl' => 'SSL',
                    '' => 'None',
                ]),
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@worknepal.com',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'From email address',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'WorkNepal',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_EMAIL,
                'description' => 'From name',
                'is_public' => false,
            ],

            // Notification Settings
            [
                'key' => 'send_welcome_email',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_NOTIFICATION,
                'description' => 'Send welcome email to new users',
                'is_public' => false,
            ],
            [
                'key' => 'send_application_confirmation',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_NOTIFICATION,
                'description' => 'Send confirmation email when user applies',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_on_new_company',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_NOTIFICATION,
                'description' => 'Notify admin when new company registers',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_on_new_job',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_NOTIFICATION,
                'description' => 'Notify admin when new job is posted',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_on_new_report',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_NOTIFICATION,
                'description' => 'Notify admin when new report is submitted',
                'is_public' => false,
            ],

            // API Settings
            [
                'key' => 'api_rate_limit',
                'value' => '60',
                'type' => Setting::TYPE_NUMBER,
                'group' => Setting::GROUP_API,
                'description' => 'API rate limit per minute',
                'is_public' => false,
            ],
            [
                'key' => 'enable_api',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => Setting::GROUP_API,
                'description' => 'Enable API access',
                'is_public' => false,
            ],

            // SEO Settings
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Google Analytics tracking ID',
                'is_public' => false,
            ],
            [
                'key' => 'facebook_pixel_id',
                'value' => '',
                'type' => Setting::TYPE_TEXT,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'Facebook Pixel ID',
                'is_public' => false,
            ],
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /dashboard/",
                'type' => Setting::TYPE_TEXTAREA,
                'group' => Setting::GROUP_GENERAL,
                'description' => 'robots.txt content',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}