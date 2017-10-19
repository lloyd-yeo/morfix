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
    protected $notRequiredTableColumn = array();
    protected $foreignTable = array();
    protected $tableResult = null;
    protected $response = array(
      "result" => null,
      "error"  => array()
    );

    public function test(){
      return "Welcome to MorfixController!";
    }

    public function response(){
      if($this->tableResult){
        $this->response['result'] = $this->tableResult;
      }else{
        ///
      }
      return json_encode($this->response);
    }

    public function create(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements["flag"] == true){
        $this->insertDB($request);
      }else{
        $this->requirementsErrorMessage($requirements['response']);
      }
      return $this->response();
    }

    public function retrieve(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      $tableRequirement = $this->checkTableRequirements($request);
      if($requirements["flag"] == true && $tableRequirement['flag'] == true){
        $this->retrieveDB($tableRequirement['condition'], $tableRequirement['order'], $tableRequirement['limit']);  
      }else{
        $this->requirementsErrorMessage($requirements['response']);
      }
      return $this->response();
    }

    public function update(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements['flag'] == true){
        $this->updateDB($request);  
      }else{
        return $this->requirementsErrorMessage($requirements['response']);
      }
      return $this->response();
    }

    public function delete(Request $request){
      $request = $request->all();
      $requirements = $this->checkRequirements();
      if($requirements['flag'] == true){
        $this->deleteDB($request);  
      }else{
        $this->requirementsErrorMessage($requirements['response']);
      }
      return $this->response();  
    }

    public function insertDB($request){
      return $this->model->insert($request);
    }

    public function retrieveDB($condition = null, $order = NULL, $limit = NULL){
      if($condition && $order && $limit){
        $this->tableResult = $this->model->where($condition)->orderBy($order)->limit($limit)->get();
      }
      else if(($condition && $order) && $limit == null) {
        $this->tableResult =  $this->model->where($condition)->orderBy($order)->get();
      }
      else if(($condition && $limit) && $order == null) {
        $this->tableResult =  $this->model->where($condition)->limit($limit)->get();
      }
      else if($condition && $order == null && $limit == null){
        $this->tableResult =  $this->model->where($condition)->get();
      }else if($condition == null && $order == null && $limit){
        $this->tableResult =  $this->model->limit($limit)->get();
      }
      else{
        $this->tableResult = $this->model->get();
      }
    }

    public function updateDB($request){
      return $this->model->where($this->condition)->update($request);
    }

    public function deleteDB($request){
      return $this->model->where($request)->whereNull("deleted_at")->update(["deleted_at" => Carbon::now()]);
    }

    public function checkRequirements(){
      $response = [
        "flag"      => true,
        "response"  => null
      ];  
      if($this->model == null){
        $response['flag'] = false;
        $response['response'] = "model_is_null";
      }
      return $response;
    }

    public function checkTableRequirements($request){
      $response = [
        "flag"          => null,
        'condition'     => null,
        'order'         => null,
        'limit'         => null
      ];
      if(isset($request['condition'])){
         $response['flag'] = false;
      }
      if(isset($request['order'])){
         $response['flag'] = false;
      }
      if(isset($request['limit'])){
        if(is_int(intval($request['limit'])) == true){
          $response['flag'] = true;
          $response['limit'] = intval($request['limit']);
        }else{
          $response['flag'] = false;
        }
      }else{$response['flag'] = false;}
      return $response;
    }

    public function requirementsErrorMessage($response){
      switch ($response) {
        case 'model_is_null':
          return $this->response["error"] = array(
                    "status"  => 100,
                    "message" => "Model is required"
                  );
          break;
        default:
          # code...
          break;
      }
      return $this->response;
    }
  
}
