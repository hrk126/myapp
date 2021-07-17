<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'price',
        'image_file',
    ];

    public function menus()
    {
        return $this->hasMany('App\Models\Menu');
    }

}
