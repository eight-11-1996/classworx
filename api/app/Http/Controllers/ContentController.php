<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\WordAudio;
use App\Save;
use App\SaveContent;
use App\AccentAudio;
use App\AccentVideo;
class ContentController extends TalkController
{
    function __construct(){
      $this->model = new Content();
      $this->notRequired = array(
        "path",
        "syllabus_path",
      );
    }
    // public function create(Request $request){
    //   $data = $request->all();
    //   $filename = Carbon::now()->toDateString() .'_'.$data['filename'];
    //   $syllabusFilename = Carbon::now()->toDateString() .'_'.$data['syllabus_filename'];
    //   $result = $request->file('file')->storeAs('sentenceAudio', $filename);
    //   $result1 = $request->file('syllabus_file')->storeAs('contentSyllabus', $syllabusFilename);
    //   $data['audio'] = $filename;
    //   $data['syllabus_audio'] = $syllabusFilename;
    //   $data['path'] = '/storage/sentence_audio/';
    //   $data['syllabus_path'] = '/storage/content_syllabus/';
    //   $this->insertDB($data);
    //   return $this->response();
    // }
    // public function update(Request $request){
    //   $data = $request->all();
    //   if(isset($data['filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['filename'];
    //     $result = $request->file('file')->storeAs('sentenceAudio', $filename);
    //     $data['audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], '/storage/sentence_audio/');
    //   }
    //   if(isset($data['syllabus_filename'])){
    //     $syllabusFilename = Carbon::now()->toDateString() .'_'.$data['syllabus_filename'];
    //     $result = $request->file('syllabus_file')->storeAs('contentSyllabus', $syllabusFilename);
    //     $data['syllabus_audio'] = $syllabusFilename;
    //     $this->deleteAudioFile($data['id'], '/storage/content_syllabus/');
    //   }else{
    //     //
    //   }
      
    //   $data['path'] = '/storage/sentence_audio/';
    //   $data['syllabus_path'] = '/storage/content_syllabus/';
    //   $this->model = new Content();
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
    //   $this->model = new Content();
    //   $result = $this->retrieveDB($parameter);
    //   $file = $path.$result[0]['audio'];
    //   if($result[0]['audio'] != null){
    //     Storage::delete($file);  
    //   }
    // }
    public function dashboard(Request $request){
      $sentenceResult = array(
        array()
      );
      $data = $request->all();
      $saveContent = null;
      $result = Content::where($data['column'], $data['clause'], $data['value'])->get();
      $audioList = AccentAudio::where($data['column'], $data['clause'], $data['value'])->get();
      $videoList = AccentVideo::where($data['column'], $data['clause'], $data['value'])->get();
      $content2d = $this->content2DArray($result);
      if(sizeof($result) > 0){
        $customResponse = array('data' => $result);
        //Split Sentence
        $i = 0;
        foreach ($result as $key) {
          $sentenceAtInitial = $key->original;
          $list = null;
          if(strpos($sentenceAtInitial, ' ')){
            $list = explode(' ', $sentenceAtInitial);
            $j = 0;
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
              $condition = null;
              $wordAudioResult = null;
              if($exResult != null){
                $condition = array('word', '=', $exResult[$exIndex]);
                $wordAudioResult = WordAudio::where('word', '=', $exResult[$exIndex])->get();
              }else{
                $condition = array('word', '=', $value);
                $wordAudioResult = WordAudio::where('word', '=', $value)->get();
              }
              if(sizeof($wordAudioResult) > 0){
                $saveCondition = array(
                  array('account_id', '=', $data['account_id']),
                  array('word_audio_id', '=', $wordAudioResult[0]->id)
                );
                $saveResult = Save::where($saveCondition)->get();
                $click = (sizeof($saveResult) > 0) ? 1 : 0;
                $sentenceResult[$i][$j++] = array('word' => $newValue, 'click' => $click, 'char' => $char, 'translation' => $wordAudioResult[0]->translation);
              }else{
                $sentenceResult[$i][$j++] = array('word' => $newValue, 'click' => 0, 'char' => $char, 'translation' => "Not Found");
              }
            }
          }
          $i++;
        }
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content' => null, 'content_array' => null, 'audio' => null, 'video' => null));
      }
      $i = 0;
      foreach ($result as $key) {
        $result[$i++]['saved'] = $this->checkSaveContent($key->id, $data['account_id']);
      }
      return response()->json(array('data' => $sentenceResult, 'message' => null, 'content' => $result, 'content_array' => $content2d, 'audio' => $audioList, 'video' => $videoList));
    }

    public function checkSaveContent($contentId, $accountId){
      $condition = array(
        array('content_id', '=', $contentId),
        array('account_id', '=', $accountId)
      );
      $result = SaveContent::where($condition)->get();
      return (sizeof($result) > 0) ? 1 : 0;
    }
    public function content2DArray($result){
      $sentenceResult = null;
      if(sizeof($result) > 0){
        //Split Sentence
        $i = 0;
        foreach ($result as $key) {
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
            //
          }
          $i++;
        }
      }else{
        return null;
      }
      return $sentenceResult;
    }

}
