
<?php

use App\Models\Match;
use App\Models\Player;
use App\Models\Result;
use App\Policies\MatchPlayerResultPolicy;

protected $policies = [
    Match::class => MatchPlayerResultPolicy::class,
    Player::class => MatchPlayerResultPolicy::class,
    Result::class => MatchPlayerResultPolicy::class,
];
