<x-mail::message>
# Hello {{ $user->name }},

We noticed you might be stuck on your relocation roadmap to {{ $pathway->country->name ?? 'your destination' }}.

To keep your application moving smoothly, it's time to complete the following step:

**{{ $step->title }}**

> {{ $step->description }}

<x-mail::button :url="config('app.frontend_url') . '/pathway'">
Continue Your Roadmap
</x-mail::button>

If you've already completed this step, simply log in and mark it as done!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
