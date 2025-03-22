<?php
namespace App\Models;

use App\Models\CRUD;

class State extends CRUD
{
    protected $table      = 'state';
    protected $primaryKey = 'id';
}
