<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\ColumnRepository;

class ColumnController extends Controller
{
    private $repository;
    
    public function __construct(ColumnRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request){
        try{
        $items=$this->repository->getRecords($request->all());
        return response()->success("Column created successfully", compact("items"));
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

    public function destroy($id){
        try{
        $this->repository->delete($id);
        return response()->success("Item deleted successfully");
        }
        catch(\Exception $e){
            return $this->handleException($e);
        }
    }
}
