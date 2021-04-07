<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * Class Gift
 *
 * @package App
 * @property string $title
 * @property text $description
 * @property decimal $price
 * @property tinyInteger $is_skype
 * @property string $start_date
 * @property tinyInteger $lesson_amount
 * @property tinyInteger $published
 * @property tinyInteger $portfolio_review
 * @property tinyInteger $mentorship
 */
class Gift extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'course_id','title', 'description', 'price', 'is_skype', 'lesson_amount', 'published', 'portfolio_review', 'mentorship'];

    protected static function boot()
    {
        parent::boot();

    }


    /**
     * Set to null if empty
     * @param $input
     */
    public function setCourseIdAttribute($input)
    {
        $this->attributes['course_id'] = $input ? $input : null;
    }


    /**
     * Set to null if empty
     * @param $input
     */
    public function setCategoryIdAttribute($input)
    {
        $this->attributes['category_id'] = $input ? $input : null;
    }


    public function getPriceAttribute()
    {
        if (($this->attributes['price'] == null)) {
            return round(0.00);
        }
        return $this->attributes['price'];
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : null;
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


}
