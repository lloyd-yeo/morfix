<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MorfixController extends Controller
{
  /*
    Author: Kennette Canales
    Version: 1.0
    Company: Morfix
    Website: www.morfix.co
  */
    protected $model = null;
    protected $condition = array();
    protected $response = array(
      "result" => null,
      "error"  => array()
    );

    public function create(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements["flag"] == true){
        $this->insertDB($request);  
      }else{
        $response = $this->requirementsErrorMessage($requirements['response']);
      }
    }

    public function retrieve(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements["flag"] == true){
        $this->insertDB($this->condition, $this->order, $this->limit, $this->sort);  
      }else{
        $response = $this->requirementsErrorMessage($requirements['response']);
      }
    }

    public function update(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements['flag'] == true){
        $response = $this->updateDB($request);  
      }else{
        return $this->requirementsErrorMessage($requirements['response']);
      }   
    }

    public function delete(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements['flag'] == true){
        $response = $this->deleteDB($request);  
      }else{
        return $this->requirementsErrorMessage($requirements['response']);
      }   
    }

    public function insertDB($request){
      return $this->model->insert($request);
    }

    public function retrieveDB($condition, $order = NULL, $limit = NULL, $sort = NULL){
      $result = null;
      if($condition && $order){
        $result = $this->model->where($condition)->whereNull('deleted_at')->orderBy($order[0], $order[1])->get();
      }
      else if($condition && $order == null){
        $result =  $this->model->where($condition)->whereNull('deleted_at')->get();
      }
      else if($condition == null && $order){
        $result =  $this->model->whereNull('deleted_at')->orderBy($order[0], $order[1])->get();
      }
      else{
      }
      return json_decode($result, true);
    }

    public function updateDB($request){
      return $this->model->where($this->condition)->update($request);
    }

    public function deleteDB($request){
      return $this->model->where($request)->whereNull("deleted_at")->update(["deleted_at" => Carbon::now()]);
    }

    public function checkRequirements(){
      $response = [
        "flag"      => null,
        "response"  => null
      ];
      return $response;
    }

    public function requirementsErrorMessage($response){
      switch ($response) {
        case 'model':
          return $response["error"] = array(
                    "status"  => 1000,
                    "message" => "Model is required"
                  );
          break;
        default:
          # code...
          break;
      }
    }
    public function responseHandler($response){
      return $response;
    }
}
