<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name', 'description'];
    public function admins()
    {
        return $this->belongsToMany(Admin::class,'role_admin');
    }
    public function permissions()
    {
        // assuming your pivot table is called role_permission
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}