<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Mentorship extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }


}
