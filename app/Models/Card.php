<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'weight', 'column_id'
    ];

    /**
     * apply filters on query
     */
    public function scopeFilters($query, array $data=[])
    {
        $query->when(!empty($data["date"]), function ($q) use ($data) {
            $q->whereDate('created_at', $data["date"]);
        });

        if(!isset($data["status"])){
            $query->withTrashed();
        }
        else if($data["status"]==0){
            $query->onlyTrashed();
        }

        return $query;
    }
}
