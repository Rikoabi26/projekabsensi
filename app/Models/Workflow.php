<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workflow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function role()
    {
        return $this->belongsTo(Role::class, foreignKey: 'role_id');
    }
}
