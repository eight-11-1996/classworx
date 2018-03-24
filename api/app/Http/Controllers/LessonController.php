<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\TopLesson;
use App\CategoryLesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LessonController extends TalkController
{
    function __construct(){
      $this->model = new Lesson();

      $this->notRequired = array(
        "accent_text",
        "accent_audio",
        "accent_video",
        "audio_path",
        "video_path",
        "grammar_tip",
        "grammar_audio",
        "grammar_audio_path",
        "culture_tip",
        "culture_audio",
        "culture_audio_path",
        "lesson_audio_path"
      );
    }

    // public function create(Request $request){
    //   $data = $request->all();
    //   $lessonFilename = Carbon::now()->toDateString() .'_'.$data['lesson_filename'];
    //   $result = $request->file('lesson_file')->storeAs('lessonAudio', $lessonFilename); 
    //   if(isset($data['audio_filename'])){
    //     $afilename = Carbon::now()->toDateString() .'_'.$data['audio_filename'];
    //     $result = $request->file('accent_audio_file')->storeAs('accentAudio', $afilename); 
    //     $data['accent_audio'] = $afilename;
    //   }
    //   if(isset($data['grammar_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['grammar_filename'];
    //     $result = $request->file('grammar_file')->storeAs('grammarAudio', $afilename); 
    //     $data['grammar_audio'] = $filename;
    //   }
    //   if(isset($data['culture_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['culture_filename'];
    //     $result = $request->file('culture_file')->storeAs('cultureAudio', $afilename); 
    //     $data['culture_audio'] = $filename;
    //   }
    //   // if(isset($data['video_filename'])){
    //   //   $vfilename = Carbon::now()->toDateString() .'_'.$data['video_filename'];
    //   //   $result = $request->file('accent_video_file')->storeAs('accentVideo', $vfilename); 
    //   //   $data['accent_video'] = $vfilename;
    //   // }
    //   else{
    //     //
    //   }
    //   $data['status'] = 'PENDING';
    //   $data['lesson_audio_path'] = '/storage/lesson_audio/';
    //   $data['grammar_audio_path'] = '/storage/grammar_audio/';
    //   $data['culture_audio_path'] = '/storage/culture_audio/';
    //   $data['audio_path'] = '/storage/accent_audio/';
    //   $data['video_path'] = '/storage/accent_video/';
    //   $data['lesson_audio'] = $lessonFilename;
    //   $this->insertDB($data);
    //   return $this->response();
    // }

    // public function update(Request $request){
    //   $data = $request->all();
    //   if(isset($data['lesson_filename'])){
    //     $lessonFilename = Carbon::now()->toDateString() .'_'.$data['lesson_filename'];
    //     $result = $request->file('lesson_file')->storeAs('lessonAudio', $lessonFilename); 
    //     $data['lesson_audio'] = $lessonFilename;
    //     $this->deleteAudioFile($data['id'], 'lessonAudio/', 'lesson_audio');
    //   }
    //   if(isset($data['grammar_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['grammar_filename'];
    //     $result = $request->file('grammar_file')->storeAs('grammarAudio', $filename); 
    //     $data['grammar_audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], 'grammarAudio/', 'grammar_audio');
    //   }
    //   if(isset($data['culture_filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['culture_filename'];
    //     $result = $request->file('culture_file')->storeAs('cultureAudio', $filename); 
    //     $data['culture_audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], 'cultureAudio/', 'culture_audio');
    //   }
    //   if(isset($data['audio_filename'])){
    //     $afilename = Carbon::now()->toDateString() .'_'.$data['audio_filename'];
    //     $result = $request->file('accent_audio_file')->storeAs('accentAudio', $afilename); 
    //     $data['accent_audio'] = $afilename;
    //     $this->deleteAudioFile($data['id'], 'accentAudio/', 'accent_audio');
    //   }
    //   // if(isset($data['video_filename'])){
    //   //   $vfilename = Carbon::now()->toDateString() .'_'.$data['video_filename'];
    //   //   $result = $request->file('accent_video_file')->storeAs('accentVideo', $vfilename); 
    //   //   $data['accent_video'] = $vfilename;
    //   //   $this->deleteAudioFile($data['id'], 'accentVideo/', 'accent_video');
    //   // }
    //   else{
    //     //
    //   }
      
    //   $data['lesson_audio_path'] = '/storage/lesson_audio/';
    //   $data['grammar_audio_path'] = '/storage/grammar_audio/';
    //   $data['culture_audio_path'] = '/storage/culture_audio/';
    //   $data['audio_path'] = '/storage/accent_audio/';
    //   $data['video_path'] = '/storage/accent_video/';
    //   $this->model = new Lesson();
    //   $this->updateDB($data);
    //   return $this->response();
    // }
    //  public function deleteAudioFile($id, $path, $column){
    //   $parameter = array( 
    //     "condition" => [array(
    //       "column" => 'id',
    //       "clause" => "=",
    //       "value"  => $id
    //     )]
    //   );
    //   $this->model = new Lesson();
    //   $result = $this->retrieveDB($parameter);
    //   $file = $path.$result[0][$column];
    //   if($result[0][$column] != null){
    //     Storage::delete($file);
    //     return true;
    //   }else{
    //     return false;
    //   }
    // }

    public function dashboard(Request $request){

        $topLesson = TopLesson::select('*')->whereNull('deleted_at')->get();;
        if(sizeof($topLesson) > 0){
            $i = 0;
            foreach ($topLesson as $key) {
               $categoryLesson = CategoryLesson::where('top_lesson_id', '=', $key->id)->whereNull('deleted_at')->get();
               if(sizeof($categoryLesson) > 0){
                $j = 0;
                foreach ($categoryLesson as $innerKey) {
                    $lesson = Lesson::where('category_lesson_id', '=', $innerKey->id)
                            ->whereNull('deleted_at')->get();
                    if(sizeof($lesson) > 0){
                      $categoryLesson[$j]['lesson'] = $lesson; 
                    }else{
                      $categoryLesson[$j]['lesson'] = null; 
                    }
                    $j++;
                }
                $topLesson[$i++]['category'] = $categoryLesson;
               }else{
                 $topLesson[$i++]['category'] = null;
               }
            }
            return response()->json(array('data' => $topLesson));
        }else{
            return response()->json(array('data' => null));
        }
    }

}
