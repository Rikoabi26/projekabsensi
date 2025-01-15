<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IzinWorkflow extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function role()
    {
        return $this->belongsTo(Role::class, foreignKey: 'role_id');
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, foreignKey: 'workflow_id');
    }

    public function user()
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }
}
