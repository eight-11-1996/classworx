<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionOption;
use Carbon\Carbon;
class QuestionOptionController extends ClassWorxController
{
	function __construct(){
		$this->model = new QuestionOption();
	}

	public function create(Request $request){
		$data = $request->all();
		$option = $data['options'];
		if(sizeof($option) > 0){
			$i = 0;
			$length = sizeof($option);
			$result = null;
			for ($i=0; $i < $length; $i++) { 
				$option[$i]['order'] = $i + 1;
				$option[$i]['question_id'] = $data['question_id'];
				if($this->checkIfExist($option[$i]) == false){
					$result = QuestionOption::insert($option[$i]);	
				}else{
					return response()->json(
						array(
							'data' => false,
							'message' => 'Already Exist!'
						)
					);		
				}
			}
			return response()->json(
				array(
					'data' => ($result !== false) ? true : false,
					'message' => null
				)
			);
		}else{
			return false;
		}
	}
	public function checkIfExist($data){
		$condition = array(
			array('question_id', '=', $data['question_id']),
			array('order', '=', $data['order'])
		);
		$result = QuestionOption::where($condition)->get();
		if(sizeof($result) > 0)
			return true;
			return false;
	}

	public function update(Request $request){
		$data = $request->all();
		$questionOptions = $data['question_options'];
		$response = array(
			"data" => null,
			"message" => null
		);
		if(sizeof($questionOptions) > 0){
			$i = 0;
			foreach ($questionOptions as $key) {
				$questionOptions[$i]['order'] = $i + 1;
				$questionOptions[$i]['question_id'] = $data['id'];
				$result = null;
				if($this->checkIfExist($questionOptions[$i]) === true){
					$qOptionData = array(
						"description" => $questionOptions[$i]['description'],
						"order" => $questionOptions[$i]['order'],
						"question_id" => $data['id'],
						"updated_at" => Carbon::now()
					);
					$result = QuestionOption::where('id', '=', $questionOptions[$i]['id'])->update($qOptionData);
				}else{
					$result = QuestionOption::insert($questionOptions[$i]);
				}
				$i++;
			}
			$response['data'] = true;
		}else{
			$response['message'] = "Empty Data";	
		}
		return response()->json($response);
	}
}
