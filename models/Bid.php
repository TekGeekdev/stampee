<?php
namespace App\Models;

use App\Models\CRUD;

class Bid extends CRUD
{
    protected $table      = 'bid';
    protected $primaryKey = 'id';
    protected $fillable   = ['date', 'bid', 'auction_id', 'user_id'];
}


// function sql max
//https://www.w3schools.com/sql/sql_min_max.asp

SELECT MAX(bid)
FROM bid
WHERE auction_id = id sur la table auction;