Hello {{ $user->name }},

A match you pinned has been updated:

**Match ID:** {{ $match->id }}  
**Tournament:** {{ $match->tournament->name }}  
**Category:** {{ $match->category->name }}  
**Stage:** {{ $match->stage }}  

**Date:** {{ $match->match_date }}  
**Time:** {{ $match->match_time }}  

**Set 1:** {{ $match->set1_team1_points }} - {{ $match->set1_team2_points }}  
**Set 2:** {{ $match->set2_team1_points }} - {{ $match->set2_team2_points }}  
**Set 3:** {{ $match->set3_team1_points }} - {{ $match->set3_team2_points }}  

@if (!empty($changes))
---
**Updated Fields:**  
@foreach($changes as $field => $change)
    @if($field == 'stage')
        - **Stage:** {{ $change['old'] }} ➝ {{ $change['new'] }}
    @elseif(strpos($field, 'set') !== false)
        - **{{ ucfirst(str_replace('_', ' ', $field)) }}:** {{ $change['old'] }} ➝ {{ $change['new'] }}
    @endif
@endforeach
@endif

Thank you,  
**Badminton Tournament System**
