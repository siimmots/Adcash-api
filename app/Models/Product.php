<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ["name", "comment", "category_id"]; // These fields can be modified from outside

    // Determines the relationship
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
