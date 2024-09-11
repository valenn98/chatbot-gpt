<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = ['context', 'total_request', 'monthly_requests'];

    protected $casts = [
        'monthly_requests' => 'array',
    ];
}
?>

