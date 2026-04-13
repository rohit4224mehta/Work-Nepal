{{-- resources/views/emails/notification.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - WorkNepal</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .info-box {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .button {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>WorkNepal</h1>
            </div>
            
            <div class="content">
                <h2 style="margin-top: 0;">{{ $title }}</h2>
                
                <p>Hello {{ $user->name }},</p>
                
                <p>{{ $message }}</p>
                
                @if(isset($data['job_id']))
                    <div class="info-box">
                        <strong>📋 Job Details:</strong><br>
                        @if(isset($data['job_title']))
                            Position: {{ $data['job_title'] }}<br>
                        @endif
                        @if(isset($data['company_name']))
                            Company: {{ $data['company_name'] }}
                        @endif
                    </div>
                @endif
                
                @if(isset($data['jobs']) && count($data['jobs']) > 0)
                    <div class="info-box">
                        <strong>🔔 Matching Jobs:</strong><br>
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach(array_slice($data['jobs'], 0, 5) as $job)
                                <li>{{ $job['title'] }}</li>
                            @endforeach
                        </ul>
                        @if(count($data['jobs']) > 5)
                            <p style="margin-top: 10px;">... and {{ count($data['jobs']) - 5 }} more jobs</p>
                        @endif
                    </div>
                @endif
                
                <div style="text-align: center;">
                    <a href="{{ $data['action_url'] ?? url('/') }}" class="button">
                        View Details
                    </a>
                </div>
                
                <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                    You're receiving this email because you have notifications enabled on WorkNepal.
                    You can change your notification preferences in your account settings.
                </p>
            </div>
            
            <div class="footer">
                <p>&copy; {{ date('Y') }} WorkNepal. All rights reserved.</p>
                <p>
                    <a href="{{ url('/') }}" style="color: #6b7280; text-decoration: none;">Home</a> | 
                    <a href="{{ route('notifications.preferences') }}" style="color: #6b7280; text-decoration: none;">Preferences</a> | 
                    <a href="{{ route('pages.contact') }}" style="color: #6b7280; text-decoration: none;">Contact</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>