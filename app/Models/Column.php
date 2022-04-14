<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Card;
class Column extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title'
    ];

    public static function boot()
    {
        parent::boot();

        //delete all related cards when column is deleted
        static::deleted(function($column) { 
            $column->cards()->delete();
        });
    }

    /**
     * Get the cards for the column
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
