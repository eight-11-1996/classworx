<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SentencePopup;
use App\WordAudio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\SaveContent;
class SentencePopupController extends TalkController
{
    function __construct(){
      $this->model = new SentencePopup();
    }

    
    // public function create(Request $request){
    //   $data = $request->all();
    //   $filename = Carbon::now()->toDateString() .'_'.$data['filename'];
    //   $result = $request->file('file')->storeAs('answerAudio', $filename);
    //   $data['audio'] = $filename;
    //   $data['path'] = '/storage/answer_audio/';
    //   $this->insertDB($data);
    //   return $this->response();
    // }

    // public function update(Request $request){
    //   $data = $request->all();
    //   if(isset($data['filename'])){
    //     $filename = Carbon::now()->toDateString() .'_'.$data['filename'];
    //     $result = $request->file('file')->storeAs('answerAudio', $filename);
    //     $data['audio'] = $filename;
    //     $this->deleteAudioFile($data['id'], '/storage/answer_audio/');
    //   }else{
    //     //
    //   }
      
    //   $data['path'] = '/storage/answer_audio/';
    //   $this->model = new Answer();
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
    //   $this->model = new Answer();
    //   $result = $this->retrieveDB($parameter);
    //   $file = $path.$result[0]['audio'];
    //   if($result[0]['audio'] != null){
    //     Storage::delete($file);  
    //   }
    // }

    public function dashboard(Request $request){
      $sentenceResult = null;
      $data = $request->all();
      $saveContentResult = $this->saveContent($data);
      $result = SentencePopup::where($data['column'], $data['clause'], $data['value'])->get();
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
        return response()->json(array('data' => $result, 'message' => null, 'content_array' => $sentenceResult, 'save_content' => $saveContentResult));
      }else{
        return response()->json(array('data' => null, 'message' => 'Content not found!', 'content_array' => null, 'save_content' => $saveContentResult));
      }
    }

    public function saveContent($data){
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['content_id', '=', $data['content_id']]
      );
      $result = SaveContent::where($condition)->get();
      if(sizeof($result) > 0){
        return false;
      }else{
        $newData = array(
          "content_id"  => $data["content_id"],
          "account_id"  => $data["account_id"],
          "created_at"  => Carbon::now()
        );
        SaveContent::insert($newData);
        return 1;
      }
    }
}
