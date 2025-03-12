@component('mail::message')
# New Match Created!

A new match has been created.

**Match ID:** {{ $matches->id }}  
**Created by:** {{ $matches->createdBy->name }}

@component('mail::button', ['url' => route('matches.singles.show', $matches->id)])
View Match Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
