<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $oldTournament;
    public $newTournament;
    public $updatedBy;

    public function __construct($oldTournament, $newTournament, $updatedBy)
    {
        $this->oldTournament = $oldTournament;
        $this->newTournament = $newTournament;
        $this->updatedBy = $updatedBy;
    }

    public function build()
    {
        return $this->subject("Tournament Updated: {$this->newTournament->name}")
                    ->view('emails.tournament_updated')
                    ->with([
                        'oldTournament' => $this->oldTournament,
                        'newTournament' => $this->newTournament,
                        'updatedBy' => $this->updatedBy,
                        'tournamentLink' => url("/tournaments/{$this->newTournament->id}")
                    ]);
    }
}
