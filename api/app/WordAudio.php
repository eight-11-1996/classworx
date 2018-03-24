<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordAudio extends APIModel
{
    protected $table = 'word_audios';
    protected $fillable = ['word', 'audio', 'translation',  'path'];
}
