<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{

    protected $fillable = [
        'id',
        'name',
        'voteCommit',
        'voteDiscard'
    ];

    public $timestamps = false;

    use HasFactory;
}
