<?php

namespace App\Repositories;

use App\Models\Column;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class ColumnRepository extends BaseRepository
{
    function __construct(Column $model)
    {
        parent::__construct($model);
    }

    public function getRecords(array $data=[]){
        return $this->model::with(["cards" => function($q){
            $q->orderBy("weight", "asc");
        }])->get();
    }

    public function store(array $data){
        $column = $this->create($data);
        $column->load("cards");
        return $column;
    }

    public function storeValidation(array $data){
        return Validator::make($data, [
            'title' => 'required|max:100'
        ]);
    }

}
