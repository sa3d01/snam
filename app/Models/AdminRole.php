<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $fillable= ['admin_id','role_id'];
    protected $table = 'role_admin';
}
