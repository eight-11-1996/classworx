<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends APIModel
{
    protected $table = 'lessons';
    protected $fillable = ['category_lesson_id', 'title','lesson_audio', 'lesson_audio_path',  'accent_text', 'accent_audio', 'audio_path','accent_video', 'video_path', 'grammar_tip', 'grammar_audio', 'grammar_audio_path', 'culture_tip', 'culture_audio', 'culture_audio_path', 'status'];

    public function content(){
      return $this->hasMany('App\Content');
    }
}
