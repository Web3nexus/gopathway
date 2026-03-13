<x-mail::message>
    # New Feature: {{ $feature->feature_name }}

    Hello,

    We just released a new feature to help you on your relocation journey: **{{ $feature->feature_name }}**.

    {{ $feature->description }}

    Log in to your dashboard now to explore what's new!

    <x-mail::button :url="config('app.frontend_url') . '/dashboard'">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    The {{ config('app.name') }} Team
</x-mail::message>