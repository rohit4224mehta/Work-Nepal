@component('mail::message')
# Welcome to WorkNepal, {{ $name }}!

Thank you for joining Nepal's trusted job platform.

To get started and access your dashboard, please **verify your email address** by clicking the button below:

@component('mail::button', ['url' => $url, 'color' => 'red'])
Verify My Email
@endcomponent

This link will expire in **60 minutes** for security reasons.

If the button doesn't work, copy and paste this link:  
{{ $url }}

**Important Nepal Safety Note**  
When applying to foreign jobs, always check official guidelines.  
Read our [Foreign Employment Safety Guide]({{ url('/pages/foreign-safety') }}) before proceeding.

If you didn't create this account, please ignore this email — no further action is required.

Thank you,<br>
**WorkNepal Team**  
Kathmandu, Nepal  
support@worknepal.com

<small style="color: #6b7280;">This is an automated message. Do not reply directly.</small>
@endcomponent