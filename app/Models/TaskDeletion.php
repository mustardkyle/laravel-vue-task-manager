<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDeletion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'task_id',
        'task_title',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_deletions';
}