<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'issue',
        'description',
        'priority',
        'category',
        'status',
        'client_id',
        'assigned_to',
        'system_id',
        'assigned_at',
        'resolution',
        'comments',
        'score'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function toDto()
    {
        return [
            'id' => $this->id,
            'issue' => $this->issue,
            'description' => $this->description,
            'priority' => $this->priority,
            'category' => $this->category,
            'status' => $this->status,
            'client' => $this->client->toDto(),
            'system' => $this->system->toDto(),
            'assigned_at' => $this->assigned_at,
            'resolution' => $this->resolution,
            'comments' => $this->comments,
        ];
    }
}
