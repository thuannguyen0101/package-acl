<?php

namespace Workable\Translate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    protected $table = 'translates';
    protected $fillable = [
        'key_language',
        'translation',
    ];
}
