<?php
namespace App\Models;

use App\Models\CRUD;

class FileToUpload extends CRUD
{
    protected $table      = 'fileToUpload';
    protected $primaryKey = 'id';
    protected $fillable   = ['file', 'position', 'description', 'stamp_id'];
}
