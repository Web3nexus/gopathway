<x-mail::message>
# {{ $type === 'new_support_ticket' ? 'New Support Message' : 'New Reply from Support' }}

Hello {{ $type === 'new_support_ticket' ? 'Admin' : $sender->name }},

{{ $type === 'new_support_ticket' 
    ? "A new support message has been received from {$sender->name} ({$sender->email})." 
    : "You have received a reply from the GoPathway support team regarding your ticket: " . ($messageObj->conversation->subject ?? 'N/A') }}

**Message:**
{{ $messageObj->body }}

<x-mail::button :url="config('app.frontend_url') . ($type === 'new_support_ticket' ? '/admin/support' : '/support')">
View Message
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
