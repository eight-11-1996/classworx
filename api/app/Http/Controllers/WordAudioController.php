<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WordAudio;
use App\WordPopup;
use App\Save;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
class WordAudioController extends TalkController
{
    function __construct(){
      $this->model = new WordAudio();
      $this->validation =  array(
        "word" => "unique:word_audios"
      ); 
      $this->notRequired = array(
        "path",
        "syllabus_path",
      );
    }

   	// public function create(Request $request){
    //   $data = $request->all();
    //   $filename = Carbon::now()->toDateString() .'_'.$data['audio'];
    //   $result = $request->file('file')->storeAs('word', $filename);
    //   $syllabusFilename = Carbon::now()->toDateString() .'_'.$data['syllabus_filename'];
    //   $result = $request->file('syllabus_file')->storeAs('wordSyllabus', $syllabusFilename);
    //   $data['audio'] = $filename;
    //   $data['syllabus_audio'] = $syllabusFilename;
    //   $data['path'] = '/storage/word/';
    //   $data['syllabus_path'] = '/storage/word_syllabus/';
    //   $this->insertDB($data);
    //   return $this->response();
   	// }

    // public function update(Request $request){
    //   $data = $request->all();
    //   if(isset($data['audio'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['audio'];
    //     $result = $request->file('file')->storeAs('word', $filename);
    //     $data['audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], 'audio');
    //   }
    //   if(isset($data['syllabus_filename'])){
    //     $syllabusFilename = Carbon::now()->toDateString() .'_'.$data['syllabus_filename'];
    //     $result = $request->file('syllabus_file')->storeAs('wordSyllabus', $syllabusFilename);
    //     $data['syllabus_audio'] = $syllabusFilename;
    //     $this->deleteAudioFile($data['id'], '/storage/word_syllabus/');
    //   }else{
    //     //
    //   }
      
    //   $data['path'] = '/storage/word/';
    //   $data['syllabus_path'] = '/storage/word_syllabus/';
    //   $this->model = new WordAudio();
    //   $this->updateDB($data);
    //   return $this->response(); 
    // }
    // public function deleteAudioFile($id, $path){
    //   $parameter = array( 
    //     "condition" => [array(
    //       "column" => "id",
    //       "clause" => "=",
    //       "value"  => $id
    //     )]
    //   );
    //   $this->model = new WordAudio();
    //   $result = $this->retrieveDB($parameter);
    //   $file = $path.$result[0][$path];
    //   if($result[0][$path] != null){
    //     Storage::delete($file);  
    //   }
    // }

    public function dashboard(Request $request){
      $data = $request->all();
      $result = WordAudio::where($data['column'], $data['clause'], $data['value'])->get();
      if(sizeof($result) > 0){
        $sentenceResult = null;
        //Split Sentence
        $wordPopupResult = WordPopup::where('word_audio_id', '=', $result[0]->id)->get();
        if(sizeof($wordPopupResult) > 0){
            $i = 0;
            foreach ($wordPopupResult as $key) {
              $sentenceAtInitial = $key->original;
              $list = null;
              $j = 0;
              if(strpos($sentenceAtInitial, ' ')){
                $list = explode(' ', $sentenceAtInitial);
                foreach ($list as $value) {
                  $exResult = null;
                  $exIndex = 0;
                  $char = null;
                  $newValue = null;
                  if(strpos($value,',')){
                    $exResult = explode(',', $value);
                    $char = ',';
                    $newValue = $exResult[0];
                  }else if(strpos($value, '?')){
                    $exResult = explode('?', $value);
                    $char = '?';
                    $newValue = $exResult[0];
                  }else{
                    $char = '';
                    $newValue = $value;
                  }
                  $wordAudioResult = WordAudio::where('word', '=', $newValue)->get();
                  $translation = null;
                  if(sizeof($wordAudioResult) > 0){
                    $translation = $wordAudioResult[0]->translation;
                  }else{
                    $translation = "Not Found";
                  }
                  $sentenceResult[$i][$j++] = array('word' => $newValue, 'char' => $char, "translation" => $translation); 
                }
              }
              else{
                $wordAudioResult = WordAudio::where('word', '=', $sentenceAtInitial)->get();
                $translation = null;
                if(sizeof($wordAudioResult) > 0){
                    $translation = $wordAudioResult[0]->translation;
                }else{
                    $translation = "Not Found";
                }
                $sentenceResult[$i][$j++] = array('word' => $sentenceAtInitial, 'char' => '', "translation" => $translation);
              }
              $i++;
            }
        }
        return response()->json(array('data' => $result, 'message' => null, 'content' => $sentenceResult, 'word' => $wordPopupResult)); 
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content' => null, 'save_content' => null, 'word' => null));
      }
    }
}
