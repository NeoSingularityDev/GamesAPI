<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'year', 'developer_id'];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
}
