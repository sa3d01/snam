<?php
namespace App\Models;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = 'permissions';
    protected $fillable = ['name','display_name','description'];
    public function roles()
    {
        // assuming your pivot table is called role_permission
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}