<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = ['name'];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function toDto()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
