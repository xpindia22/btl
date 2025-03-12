@component('mail::message')
# New Match Created!

A new **{{ $matchType }}** match has been created.

**Match ID:** {{ $matches->id }}  
**Created by:** {{ $matches->createdBy->name }}

@if($matchType == 'singles')
**Player 1:** {{ $matches->player1->name }}  
**Player 2:** {{ $matches->player2->name }}
@elseif($matchType == 'doubles')
**Team 1 Players:**  
- {{ $matches->team1Player1->name }}  
- {{ $matches->team1Player2->name }}

**Team 2 Players:**  
- {{ $matches->team2Player1->name }}  
- {{ $matches->team2Player2->name }}
@endif

@component('mail::button', ['url' => $matchType === 'doubles' ? route('matches.doubles.show', $matches->id) : route('matches.singles.show', $matches->id)])
View Match Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
