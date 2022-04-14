<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\CardRepository;

class CardController extends Controller
{
    private $repository;
    
    public function __construct(CardRepository $repository)
    {
           $this->repository = $repository;
    }

    public function index(Request $request){
        try{
        $this->repository->indexValidation($request->all())->validate();
        $records=$this->repository->getRecords($request->all());
        return response()->json($records);
        }
        catch(\Exception $e){
            return $this->handleException($e);
        }
    }

    public function store(Request $request){
        try{          
        DB::beginTransaction();
        $data = $request->input();
        $this->repository->storeValidation($data)->validate();   
        $item = $this->repository->store($data);
        DB::commit();
        return response()->success("Column created successfully", compact("item"));
        }
        catch(\Exception $e){
            DB::rollBack();
            DB::commit();
            return $this->handleException($e);
        }
    }

    public function update($id, Request $request){
        try{
        DB::beginTransaction();
        $data = $request->input();
        $item = $this->repository->update($id, $data);
        DB::commit();
        return response()->success("Item updated successfully", compact("item"));
        }
        catch(\Exception $e){
            DB::rollBack();
            DB::commit();
            return $this->handleException($e);
        }
    }
}
