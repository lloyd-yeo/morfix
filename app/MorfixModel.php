<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class MorfixModel extends Model
{
    use SoftDeletes;
    public $parent = [];

    // public function children($childName){
    //   $joinedTable = $this;
    //   foreach($this->children as $childName => $child){
    //     $joinedTable = $joinedTable
    //   }
    //   return $joinedTable;
    // }
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    // public function getTableDetail(){
    //   return $this->getConnection()->getSchemaBuilder()->listTableDetails("inventory");
    // }
    public function newModel($table, $attribute){
      $modelName = "App\\".str_replace(' ', '', ucwords(str_replace('_', ' ', str_singular($table))));
      return new $modelName($attribute);
    }
}
