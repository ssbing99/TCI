<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseStudentZoom extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['course_id', 'meeting_id', 'topic', 'description', 'start_at', 'duration', 'password', 'student_limit', 'start_url', 'join_url'];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function zoomSlotBookings()
    {
        return $this->hasMany(CourseStudentZoomBooking::class);
    }
}
