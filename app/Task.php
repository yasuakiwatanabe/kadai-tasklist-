<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['status','content','task','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}