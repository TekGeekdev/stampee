<?php
namespace App\Models;

use App\Models\CRUD;

class Favorite extends CRUD
{
    protected $table      = 'favorite';
    protected $primaryKey = ['auction_id', 'user_id'];
}
