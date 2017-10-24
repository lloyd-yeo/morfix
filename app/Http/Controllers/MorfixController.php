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
	protected $model = NULL;
	protected $notRequiredTableColumn = [];
	protected $foreignTable = [];
	protected $tableResult = NULL;
	protected $response = [
		"result" => NULL,
		"error"  => [],
	];

	public function test()
	{
		return "Welcome to MorfixController!";
	}

	public function response()
	{
		if ($this->tableResult) {
			$this->response['result'] = $this->tableResult;
		} else {
			///
		}

		return json_encode($this->response);
	}

	public function create(Request $request)
	{
		$request      = $request->all();
		$requirements = $this->checkRequirements();
		if ($requirements["flag"] == TRUE) {
			$this->insertDB($request);
		} else {
			$this->requirementsErrorMessage($requirements['response']);
		}

		return $this->response();
	}

	public function retrieve(Request $request)
	{
		$request          = $request->all();
		$requirements     = $this->checkRequirements();
		$tableRequirement = $this->checkTableRequirements($request);
		if ($requirements["flag"] == TRUE && $tableRequirement['flag'] == TRUE) {
			$this->retrieveDB($tableRequirement['condition'], $tableRequirement['order'], $tableRequirement['limit']);
		} else {
			$this->requirementsErrorMessage($requirements['response']);
		}

		return $this->response();
	}

	public function update(Request $request)
	{
		$request      = $request->all();
		$requirements = $this->checkRequirements();
		if ($requirements['flag'] == TRUE) {
			$this->updateDB($request);
		} else {
			return $this->requirementsErrorMessage($requirements['response']);
		}

		return $this->response();
	}

	public function delete(Request $request)
	{
		$request      = $request->all();
		$requirements = $this->checkRequirements();
		if ($requirements['flag'] == TRUE) {
			$this->deleteDB($request);
		} else {
			$this->requirementsErrorMessage($requirements['response']);
		}

		return $this->response();
	}

	public function insertDB($request)
	{
		return $this->model->insert($request);
	}

	public function retrieveDB($condition = NULL, $order = NULL, $limit = NULL)
	{
		if ($condition && $order && $limit) {
			$this->tableResult = $this->model->where($condition)->orderBy($order)->limit($limit)->get();
		} else {
			if (($condition && $order) && $limit == NULL) {
				$this->tableResult = $this->model->where($condition)->orderBy($order)->get();
			} else {
				if (($condition && $limit) && $order == NULL) {
					$this->tableResult = $this->model->where($condition)->limit($limit)->get();
				} else {
					if ($condition && $order == NULL && $limit == NULL) {
						$this->tableResult = $this->model->where($condition)->get();
					} else {
						if ($condition == NULL && $order == NULL && $limit) {
							$this->tableResult = $this->model->limit($limit)->get();
						} else {
							$this->tableResult = $this->model->get();
						}
					}
				}
			}
		}
	}

	public function updateDB($request)
	{
		return $this->model->where($this->condition)->update($request);
	}

	public function deleteDB($request)
	{
		return $this->model->where($request)->whereNull("deleted_at")->update([ "deleted_at" => Carbon::now() ]);
	}

	public function checkRequirements()
	{
		$response = [
			"flag"     => TRUE,
			"response" => NULL,
		];
		if ($this->model == NULL) {
			$response['flag']     = FALSE;
			$response['response'] = "model_is_null";
		}

		return $response;
	}

	public function checkTableRequirements($request)
	{
		$response = [
			"flag"      => NULL,
			'condition' => NULL,
			'order'     => NULL,
			'limit'     => NULL,
		];
		if (isset($request['condition'])) {
			$response['flag'] = FALSE;
		}
		if (isset($request['order'])) {
			$response['flag'] = FALSE;
		}
		if (isset($request['limit'])) {
			if (is_int(intval($request['limit'])) == TRUE) {
				$response['flag']  = TRUE;
				$response['limit'] = intval($request['limit']);
			} else {
				$response['flag'] = FALSE;
			}
		} else {
			$response['flag'] = FALSE;
		}

		return $response;
	}

	public function requirementsErrorMessage($response)
	{
		switch ($response) {
			case 'model_is_null':
				return $this->response["error"] = [
					"status"  => 100,
					"message" => "Model is required",
				];
				break;
			default:
				# code...
				break;
		}

		return $this->response;
	}

}
