@component('mail::message')
# New Doubles Match Created!

A new doubles match has been created.

**Match ID:** {{ $matches->id }}  
**Tournament:** {{ optional($matches->tournament)->name }}  
**Category:** {{ optional($matches->category)->name }}

**Team 1 Players:**  
- {{ optional($matches->team1Player1)->name }}  
- {{ optional($matches->team1Player2)->name }}

**Team 2 Players:**  
- {{ optional($matches->team2Player1)->name }}  
- {{ optional($matches->team2Player2)->name }}

**Stage:** {{ $matches->stage }}  
**Date:** {{ $matches->match_date }}  
**Time:** {{ $matches->match_time }}

@if(!is_null($matches->set1_team1_points) && !is_null($matches->set1_team2_points))
**Set 1 Score:** {{ $matches->set1_team1_points }} - {{ $matches->set1_team2_points }}
@endif

@if(!is_null($matches->set2_team1_points) && !is_null($matches->set2_team2_points))
**Set 2 Score:** {{ $matches->set2_team1_points }} - {{ $matches->set2_team2_points }}
@endif

@if(!is_null($matches->set3_team1_points) && !is_null($matches->set3_team2_points))
**Set 3 Score:** {{ $matches->set3_team1_points }} - {{ $matches->set3_team2_points }}
@endif

@component('mail::button', ['url' => route('matches.doubles.show', $matches->id)])
View Doubles Match Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
