namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'dob', 'age', 'sex', 'uid', 'created_by'
    ];

    // Many-to-many relationship with User via the player_access pivot table.
    public function users()
    {
        return $this->belongsToMany(User::class, 'player_access');
    }
}
