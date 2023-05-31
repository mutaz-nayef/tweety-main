<?php

namespace App\Models;

use App\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tweet extends Model
{
    use HasFactory;
    use Likable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
