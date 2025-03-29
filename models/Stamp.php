<?php
namespace App\Models;

use App\Models\CRUD;

class Stamp extends CRUD
{
    protected $table      = 'stamp';
    protected $primaryKey = 'id';
    protected $fillable   = ['name', 'dateRelease', 'draw', 'width', 'height', 'certified', 'color_id', 'user_id', 'countryOrigin_id', 'conditions_id', 'content'];
}
