<?php
namespace App\Models;

use App\Models\CRUD;

class Comments extends CRUD
{
    protected $table      = 'comments';
    protected $primaryKey = 'id';
    protected $fillable   = ['content', 'date', 'auction_id'];
}
