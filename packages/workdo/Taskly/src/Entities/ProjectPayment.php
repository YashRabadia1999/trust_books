<?php

namespace Workdo\Taskly\Entities;

use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    protected $fillable = [
        'project_id',
        'amount',
        'type',
        'task_id',
        'date',
        'notes',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function task()
    {
        return $this->hasOne('Workdo\Taskly\Entities\Task', 'id', 'task_id');
    }
}
