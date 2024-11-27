<?php

namespace Workable\Translate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    protected $table = 'translates';
    protected $fillable = [
        'language_code',
        'key',
        'translation',
    ];
}
