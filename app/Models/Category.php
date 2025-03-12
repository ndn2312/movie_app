<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function movie(){
        return $this->hasMany(Movie::class)->orderBy('id','ASC');
    }
    use SoftDeletes;

}