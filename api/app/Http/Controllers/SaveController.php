<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Save;
use App\WordAudio;
use Illuminate\Support\Facades\DB;
class SaveController extends TalkController
{
    function __construct(){
      $this->model = new Save();
    }

    public function create(Request $request){
      $data = $request->all();
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['word_audio_id', '=', $data['word_audio_id']]
      );
      $result = Save::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => null, 'message' => 'Duplicate Entry'));
      }else{
        $this->insertDB($data);
        return $this->response();
      }
    }

    public function retrieveHistory(Request $request){
      $data = $request->all();
      $condition = array(
          array('words_saves.account_id', '=', $data['account_id']),
          array('words_saves.deleted_at', '=', null)
      );
      $response = DB::table('words_saves')
                  ->where($condition)
                  ->leftJoin('word_audios', 'word_audios.id', '=', 'words_saves.word_audio_id')
                  ->orderBy('word_audios.translation', 'asc')
                  ->get();
      return response()->json(array('data' => $response));
    }

    public function customRetrieve(Request $request){
      $data = $request->all();
      $condition = array(
        ['account_id', '=', $data['account_id']],
        ['word_audio_id', '=', $data['word_audio_id']]
      );
      $result = Save::where($condition)->get();
      if(sizeof($result) > 0){
        return response()->json(array('data' => 1));
      }else{
        return response()->json(array('data' => 0));
      }
    }
}
