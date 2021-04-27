<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 *
 * @package App
 * @property string $user_id
 * @property string $submission_id
 * @property string $teacher_id
 * @property text $title
 * @property text $description
 * @property string $unread
 */
class Log extends Model
{
    protected $fillable = ['user_id', 'submission_id', 'teacher_id', 'title', 'description', 'unread'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }


}
