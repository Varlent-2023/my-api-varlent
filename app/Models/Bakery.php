<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

/**
 * Class Bakery.
 * @author Varlent <varlent.422023028@civitas.ukrida.ac.id>
 * 
 * @OA\Schema(
 *     description="Bread model",
 *     title="Bread model",
 *     required={"id", "product_name"},
 *     @OA\Xml(
 *         name="Bread"
 *     )
 * )
 */ 

class Bakery extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'bakeries';
    protected $fillable = [
        'id',
        'product_name',
        'category',
        'availability',
        'expiration_date',
        'images',        
        'description',
        'price',           
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function data_adder(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
