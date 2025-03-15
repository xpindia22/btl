<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tournament_id', 'category_id', 'amount', 
        'payment_method', 'transaction_id', 'status', 'discount_amount'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tournament() {
        return $this->belongsTo(Tournament::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
