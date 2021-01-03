<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


/**
 * Class Course
 *
 * @package App
 * @property string $title
 * @property string $slug
 * @property text $description
 * @property decimal $price
 * @property string $images
 * @property string $start_date
 * @property tinyInteger $published
 */

class Workshop extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'description', 'enrolment_details', 'upcoming_workshop', 'price', 'deposit', 'balance', 'single_supplement', 'images','course_video', 'workshop_date', 'published', 'free', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $appends = ['image'];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->whereHas('teachers', function ($q) {
                        $q->where('workshop_user.user_id', '=', auth()->user()->id);
                    });
                });
            }
        }

        static::deleting(function ($workshop) { // before delete() method call this
            if ($workshop->isForceDeleting()) {
//                if (File::exists(public_path('/storage/uploads/' . $workshop->images))) {
//                    File::delete(public_path('/storage/uploads/' . $workshop->images));
//                    File::delete(public_path('/storage/uploads/thumb/' . $workshop->images));
//                }
            }
        });


    }

    public function getImageAttribute()
    {
        $items = [];
        if ($this->images != null) {
            if(str_contains($this->images, ',')){
                foreach (explode(',',$this->images) as $image){
                    array_push($items, $image);
                }
            }else{
                array_push($items, $this->images);
//                array_push($items, url('storage/uploads/'.$this->images));
            }

        }
        return $items;
    }

    public function getFirstImage()
    {
        if ($this->images != null) {
            if(str_contains($this->images, ',')){
                \Log::info(explode(',',$this->images)[0]);

                return url('storage/uploads/'.explode(',',$this->images)[0]);
            }else{
                return url('storage/uploads/'.$this->images);
            }

        }else{
            return url('assets_new/images/workshop-img-1.jpg');
        }
        return NULL;
    }

    public function getPriceAttribute()
    {
        if (($this->attributes['price'] == null)) {
            return round(0.00);
        }
        return $this->attributes['price'];
    }

    public function getDepositAttribute()
    {
        if (($this->attributes['deposit'] == null)) {
            return round(0.00);
        }
        return $this->attributes['deposit'];
    }

    public function getBalanceAttribute()
    {
        if (($this->attributes['balance'] == null)) {
            return round(0.00);
        }
        return $this->attributes['balance'];
    }

    public function getSingleSupplementAttribute()
    {
        if (($this->attributes['single_supplement'] == null)) {
            return round(0.00);
        }
        return $this->attributes['single_supplement'];
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : null;
    }
    public function setDepositAttribute($input)
    {
        $this->attributes['deposit'] = $input ? $input : null;
    }
    public function setBalanceAttribute($input)
    {
        $this->attributes['balance'] = $input ? $input : null;
    }
    public function setSingleSupplementAttribute($input)
    {
        $this->attributes['single_supplement'] = $input ? $input : null;
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'workshop_user')->withPivot('user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'workshop_student')->withTimestamps()->withPivot(['rating']);
    }

//    public function reviews()
//    {
//        return $this->morphMany('App\Models\Review', 'reviewable');
//    }

    public function scopeOfTeacher($query)
    {
        if (!Auth::user()->isAdmin()) {
            return $query->whereHas('teachers', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }

    public function mediaVideo()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);

    }
}
