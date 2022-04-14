<?php

namespace App\Repositories;

use App\Models\Card;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CardRepository extends BaseRepository
{
    function __construct(Card $model)
    {
        parent::__construct($model);
    }

    public function getRecords(array $data=[]){
        return $this->model::select("id", "title", "created_at", "description", "deleted_at")->filters($data)->get();
    }

    public function indexValidation(array $data){
        return Validator::make($data, [
            'date'      => ['date_format:Y-m-d'],
            'status'=> Rule::in([1,0])
        ]);
    }

    public function storeValidation(array $data){
        return Validator::make($data, [
            'title'      => 'required|max:100',
            'description'     => 'required|max:1000',
            'weight'  => 'required',
            'column_id'  => 'required',
        ]);
    }
    public function updateValidation(array $data){
        return Validator::make($data, [
            'title'      => 'required|max:100',
            'description'     => 'required|max:1000',
            'weight'  => 'required',
            'column_id'  => 'required',
        ]);
    }

}
