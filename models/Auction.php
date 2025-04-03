<?php
namespace App\Models;

use App\Models\CRUD;

class Auction extends CRUD
{
    protected $table      = 'auction';
    protected $primaryKey = 'id';
    protected $fillable   = ['name', 'dateStart', 'dateFinish', 'startPrice', 'lordLike', 'state_id', 'stamp_id'];
}
