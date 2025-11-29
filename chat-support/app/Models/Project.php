<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Optional: If you want to allow mass assignment
    protected $fillable = ['name', 'description', 'image_url'];
}

