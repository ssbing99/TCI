<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_link', 'insta_link', 'twitter_link', 'linkedin_link', 'payment_method', 'payment_details', 'title', 'description'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->belongsTo(User::class);
    }
}
