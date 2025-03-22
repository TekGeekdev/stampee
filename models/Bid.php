<?php
namespace App\Models;

use App\Models\CRUD;

class Bid extends CRUD
{
    protected $table      = 'bid';
    protected $primaryKey = 'id';
    protected $fillable   = ['date', 'bid', 'auction_id', 'user_id'];
}
