<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MorfixController extends Controller
{
	protected $model = NULL;
	protected $foreignTable = [];
	protected $editableForeignTable = [];
	protected $requiredForeignTable = [];
	protected $response = [
		"data"              => NULL,
		"error"             => [],// {status, message}
		"debug"             => NULL,
		"request_timestamp" => 0,
	];

	protected $notRequired = [];
	protected $responseType = 'json';
	protected $rawRequest = NULL;
	protected $singleImageFileUpload = [];


	public function test()
	{
		return "Welcome to MorfixController!";
	}

	public function response()
	{
		$this->response["request_timestamp"] = date("Y-m-d h:i:s");
		if ($this->responseType == 'array') {
			return $this->response;
		} else {
			return response()->json($this->response);
		}
	}

	public function create(Request $request)
	{
		$this->rawRequest = $request;
		$this->insertDB($request->all());

		return $this->response();
	}

	public function retrieve(Request $request)
	{
		$this->rawRequest = $request;
		$this->retrieveDB($request->all());

		return $this->response();
	}

	public function update(Request $request)
	{
		$this->rawRequest = $request;
		if ($request->hasFile('image_file')) {
		} else {
		}
		$this->updateDB($request->all());

		return $this->response();
	}

	public function delete(Request $request)
	{
		$this->deleteDB($request->all());

		return $this->response();
	}

	public function insertDB($request)
	{
		$tableColumns       = $this->model->getTableColumns();
		$this->tableColumns = $tableColumns;
		if (!$this->isValid($request, "create")) {
			return $this->response();
		}
		unset($tableColumns[0]);
		foreach ($tableColumns as $columnName) {
			if (isset($request[$columnName])) {
				$this->model->$columnName = $request[$columnName];
			} else if (isset($this->defaultValue[$columnName])) {
				$this->model->$columnName = $this->defaultValue[$columnName];
			}
		}
		$this->model->save();
		$childID = [];
		if ($this->model->id && count($this->singleImageFileUpload)) {
			// for($x = 0; $x < count($this->singleImageFileUpload); $x++){
			//   $this->uploadSingleImageFile(
			//     $this->model->id,
			//     $this->singleImageFileUpload[$x]['name'],
			//     $this->singleImageFileUpload[$x]['path'],
			//     $this->singleImageFileUpload[$x]['column']
			//   );
			// }
		}
		if ($this->model->id && $this->editableForeignTable) {
			foreach ($this->editableForeignTable as $childTable) {
				if (isset($request[$childTable]) && $request[$childTable]) {
					$child = $request[$childTable];
					if (count(array_filter(array_keys($child), 'is_string')) > 0) {//associative
						if (!isset($childID[$childTable])) {
							$childID[$childTable] = [];
						}
						$child[str_singular($this->model->getTable()) . '_id'] = $this->model->id;
						$foreignTable                                          = $this->model->newModel($childTable, $child);
						foreach ($child as $childKey => $childValue) {
							$foreignTable->$childKey = $childValue;
						}
						$result                 = $this->model->find($this->model->id)->$childTable()->save($foreignTable);
						$childID[$childTable][] = $result["id"];
					} else { // list
						foreach ($child as $childValue) {
							if (!isset($childID[$childTable])) {
								$childID[$childTable] = [];
							}
							$childValue[str_singular($this->model->getTable()) . '_id'] = $this->model->id;
							$foreignTable                                               = $this->model->newModel($childTable, $childValue);
							foreach ($childValue as $childValueKey => $childValueValue) {
								if ($childValueValue == NULL || $childValueValue == "" || empty($childValueValue)) {
									$foreignTable->$childValueKey = $childValueValue;
								}
							}
							$result                 = $this->model->find($this->model->id)->$childTable()->save($foreignTable);
							$childID[$childTable][] = $result["id"];
						}
					}
				}
			}
			$response = $this->model->id;
			if (count($childID)) {
				$childID["id"] = $this->model->id;;
				$response = $childID;
			}
			$this->response["result"] = $response;

			return $response;
		} else {
			if ($this->model->id) {
				$this->response["result"] = $this->model->id;

				return TRUE;
			} else {
				$this->response["error"]["status"]  = 1;
				$this->response["error"]["message"] = "Failed to create entry in database";

				return FALSE;
			}
		}
	}

	public function isValid($request, $action = "create", $subTableName = FALSE)
	{
		unset($this->tableColumns[0]);
		array_pop($this->tableColumns);//deleted at
		array_pop($this->tableColumns);//updated at
		array_pop($this->tableColumns);//created at
		foreach ($this->tableColumns as $column) {
			$this->validation[$column] = (isset($this->validation[$column])) ? $this->validation[$column] : '';
			if (!in_array($column, $this->notRequired) && !isset($this->defaultValue[$column])) {//requiring all field by default
				if ($action !== "update") {
					$this->validation[$column] = $this->validation[$column] . ($this->validation[$column] ? "| " : "") . "required";
				} else if ($action === "update") {
					if (in_array($column, $request)) {
						$this->validation[$column] = $this->validation[$column] . ($this->validation[$column] ? "| " : "") . "required";
					} else {

						unset($this->validation[$column]);
					}
				}
			}
		}
		if ($action == "update") {
			$this->validation["id"] = "required";
			if (!isset($request["id"])) {
				$this->response["error"]["status"]  = 102;
				$this->response["error"]["message"] = "ID required";

				return FALSE;
			}
		}
		if (count($this->validation)) {
			foreach ($this->validation as $validationKey => $validationValue) {
				if ($action == "update") {
					if (strpos($validationValue, "unique") !== FALSE) { //check if rule has unique
						$rules = explode("|", $this->validation[$validationKey]);
						foreach ($rules as $ruleKey => $ruleValue) { //find the unique rule
							if (strpos($ruleValue, "unique") !== FALSE) {
								$rules[$ruleKey] = Rule::unique(str_replace("unique:", "", $ruleValue), $validationKey)->ignore($request["id"], "id");
							}
						}
						$this->validation[$validationKey] = $rules;
					}
				}
				if (strpos($validationKey, "_id") !== FALSE) {
					$table = explode(".", str_plural(str_replace("_id", "", $validationKey)));
					$table = (count($table) > 1) ? $table[1] : $table[0];
					if (strpos($validationKey, "parent") !== FALSE) {
						$table = $this->model->getTable();
					}
					$this->validation[$validationKey] = $this->validation[$validationKey] . "|exists:" . $table . ",id";
				}
			}
			$validator = Validator::make($request, $this->validation);
			if ($validator->fails()) {
				if (!$subTableName) {
					$this->response["error"]["status"]  = 100;
					$this->response["error"]["message"] = $validator->errors()->toArray();
				}

				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function retrieveDB($request)
	{
		$responseType        = isset($request['response_type']) ? $request['response_type'] : 'json';
		$allowedForeignTable = array_merge($this->foreignTable, $this->editableForeignTable, $this->requiredForeignTable);
		$tableName           = $this->model->getTable();
		$singularTableName   = str_singular($tableName);
		$tableColumns        = $this->model->getTableColumns();
		$condition           = isset($request['condition']) ? $this->initCondition($request['condition']) : [ 'main_table' => [], 'foreign_table' => [] ];
		if (isset($request['with_foreign_table'])) {
			$foreignTable = [];
			foreach ($request['with_foreign_table'] as $tempForeignTable) {
				if (in_array($tempForeignTable, $allowedForeignTable)) { // check if
					$foreignTable[] = $tempForeignTable;
					if (isset($condition['foreign_table'][str_plural($tempForeignTable)])) {

						$this->model = $this->model->whereHas($tempForeignTable, function ($q) use ($condition, $tempForeignTable) {
							$tempForeignTablePlural = str_plural($tempForeignTable);
							for ($x = 0; $x < count($condition['foreign_table'][$tempForeignTablePlural]); $x++) {
								$column = $condition['foreign_table'][$tempForeignTablePlural][$x]['column'];
								$value  = $condition['foreign_table'][$tempForeignTablePlural][$x]['value'];
								$clause = isset($condition['foreign_table'][$tempForeignTablePlural][$x]['clause']) ? $condition['foreign_table'][$tempForeignTablePlural][$x]['clause'] : '=';
								$q->where($column, $clause, $value);
							}
						});

					}
				}
			}
			if (count($foreignTable)) {
				$this->model = $this->model->with($foreignTable);
			}
		}
		if (count($condition['main_table'])) {
			$this->addCondition($condition['main_table']);
		}
		if (isset($request["id"])) {
			$this->model = $this->model->where($tableName . ".id", "=", $request["id"]);
		} else {
			(isset($request['sort'])) ? $this->addOrderBy($request['sort']) : NULL;
			if (isset($request['limit'])) {
				$this->response['total_entries'] = $this->model->count();
				$this->model                     = $this->model->limit($request['limit']);
			}
			(isset($request['offset'])) ? $this->model = $this->model->offset($request['offset'] * 1) : NULL;

		}
		if (isset($request['with_soft_delete'])) {
			$this->model = $this->model->withTrashed();
		}

		for ($x = 0; $x < count($tableColumns); $x++) {
			$tableColumns[$x] = $tableName . '.' . $tableColumns[$x];
		}

		$result = $this->model->get($tableColumns)->toArray();
		if (count($result)) {
			$this->response["data"] = $result;
			if (isset($request["id"])) {
				$this->response["data"] = $this->response["data"][0];
			}
		} else {
			$this->response["error"][] = [
				"status"  => 200,
				"message" => "No Result",
			];
		}

		return $result;
	}

	public function addCondition($conditions)
	{
		/*
		  column, clause, value
		*/
		if ($conditions) {
			foreach ($conditions as $condition) {
				/*Table.Column, Clause, Value*/
				$condition["clause"] = (isset($condition["clause"])) ? $condition["clause"] : "=";
				$condition["value"]  = (isset($condition["value"])) ? $condition["value"] : NULL;
				switch ($condition["clause"]) {
					default :
						$this->model = $this->model->where($condition["column"], $condition["clause"], $condition["value"]);
				}
			}
		}
	}

	public function addOrderBy($sort)
	{
		foreach ($sort as $column => $order) {
			$this->model = $this->model->orderBy($column, $order);
		}
	}


	public function updateDB($request, $noFile = FALSE)
	{
		$responseType       = isset($request['response_type']) ? $request['response_type'] : 'json';
		$tableColumns       = $this->model->getTableColumns();
		$this->tableColumns = $tableColumns;
		$tableColumns       = $this->model->getTableColumns();

		$this->tableColumns = $tableColumns;
		if (!$this->isValid($request, "update")) {
			return $this->output();
		}
		$this->model = $this->model->find($request["id"]);
		foreach ($this->tableColumns as $columnName) {
			if (isset($request[$columnName])) {
				$this->model->$columnName = $request[$columnName];//setting attribute
			} else if (isset($this->defaultValue[$columnName]) && isset($request[$columnName])) {
				$this->model->$columnName = $this->defaultValue[$columnName];
			}

		}
		$result = $this->model->save();
		if ($result && count($this->singleImageFileUpload) && !$noFile) {
			$id = $this->model->id;
			// for($x = 0; $x < count($this->singleImageFileUpload); $x++){
			//   $this->uploadSingleImageFile(
			//     $id,
			//     $this->singleImageFileUpload[$x]['name'],
			//     $this->singleImageFileUpload[$x]['path'],
			//     $this->singleImageFileUpload[$x]['column'],
			//     $this->singleImageFileUpload[$x]['replace']
			//   );
			// }
		}
		if ($result && $this->editableForeignTable) {
			$childID = [];
			foreach ($this->editableForeignTable as $childTable => $childTableValue) {
				if (is_string($childTableValue)) {
					$childTable = $childTableValue;
				}
				if (isset($request[$childTable]) && $request[$childTable]) {
					$child = $request[$childTable];
					if (count(array_filter(array_keys($child), 'is_string')) > 0) {//associative
						if (!isset($childID[$childTable])) {
							$childID[$childTable] = [];
						}
						$result = FALSE;
						if (isset($child["id"]) && $child["id"] * 1) { // update
							$pk = $child["id"];
							unset($child["id"]);
							$result = $this->model->find($this->model->id)->$childTable()->where('id', $pk)->where(str_singular($this->model->getTable()) . '_id', $request["id"])->update($child);
						} else if (!isset($childTableValue['no_create_on_update'])) {
							$result = $this->model->find($this->model->id)->$childTable()->create($child)->id;
						}
						$childID[$childTable] = $result;
					} else { // list
						foreach ($child as $childValue) {
							if (!isset($childID[$childTable])) {
								$childID[$childTable] = [];
							}
							$result = FALSE;
							if (isset($childValue["id"]) && $childValue["id"] * 1) {//update
								$pk = $childValue["id"];
								unset($childValue["id"]);
								$foreignTable = $this->model->find($this->model->id)->$childTable()
								                            ->where('id', $pk)
								                            ->where(str_singular($this->model->getTable()) . '_id', $request["id"]);
								foreach ($childValue as $childValueKey => $childValueValue) {
									if ($childValueValue == NULL || $childValueValue == "") {
										$foreignTable->$childValueKey = $childValueValue;
									}
								}
								$result = $foreignTable
									->update($childValue);
								// $foreignTable->save($foreignTable);

							} else { //create
								$childValue[str_singular($this->model->getTable()) . '_id'] = $this->model->id;
								$foreignTable                                               = $this->model->newModel($childTable, $childValue);
								// foreach($childValue as $childValueKey => $childValueValue){
								//   if($childValueValue == null || $childValueValue == ""){
								//     $foreignTable->$childValueKey = $childValueValue;
								//   }
								// }
								$result = $this->model->find($this->model->id)->$childTable()->save($foreignTable)->id;
							}
							$childID[$childTable][] = $result;
						}
					}
				}
				if (isset($request['deleted_foreign_table'][$childTable])) {
					for ($x = 0; $x < count($request['deleted_foreign_table'][$childTable]); $x++) {
						$this->model->find($this->model->id)->$childTable()->where('id', $request['deleted_foreign_table'][$childTable][$x])->delete();
					}
				}
			}

			$response = $this->model->id;
			if (count($childID)) {
				$childID["id"] = $response;
				$response      = $childID;
			}
			$this->response["data"] = $response;

			return $response;
		} else {
			if ($result) {
				$this->response["data"] = $result;
			} else {
				$this->response["error"] = "Failed to update entry";
			}
		}
	}

	public function deleteDB($request)
	{
		$responseType = isset($request['response_type']) ? $request['response_type'] : 'json';
		$validator    = Validator::make($request, [ "id" => "required" ]);
		if ($validator->fails()) {
			$this->response["error"]["status"]  = 101;
			$this->response["error"]["message"] = $validator->errors()->toArray();

			return FALSE;
		}
		$this->response["data"] = $this->model->destroy($request["id"]);
	}

}
