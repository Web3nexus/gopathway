@component('mail::message')
# New Scholarships Found

The system has discovered new scholarships that require your review.

@component('mail::table')
| Title | Provider | Deadline |
| :--- | :--- | :--- |
@foreach($summary as $item)
| {{ $item['title'] }} | {{ $item['provider'] }} | {{ $item['deadline'] ?? 'N/A' }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => config('app.url') . '/admin/scholarships'])
Review Scholarships
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
