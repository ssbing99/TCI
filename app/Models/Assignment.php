<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Support\Facades\File;
use Mtownsend\ReadTime\ReadTime;


class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'assignment_image', 'summary', 'full_text', 'position', 'downloadable_files', 'free_lesson', 'published', 'lesson_id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($lesson) { // before delete() method call this
            if ($lesson->isForceDeleting()) {
                $media = $lesson->media;
                foreach ($media as $item) {
                    if (File::exists(public_path('/storage/uploads/' . $item->name))) {
                        File::delete(public_path('/storage/uploads/' . $item->name));
                    }
                }
                $lesson->media()->delete();
            }

        });
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setLessonIdAttribute($input)
    {
        $this->attributes['lesson_id'] = $input ? $input : null;
    }

    public function getImageAttribute()
    {
        if ($this->attributes['lesson_image'] != NULL) {
            return url('storage/uploads/'.$this->lesson_image);
        }
        return NULL;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPositionAttribute($input)
    {
        $this->attributes['position'] = $input ? $input : null;
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function downloadableMedia()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed', 'lesson_pdf', 'lesson_audio'];

        return $this->morphMany(Media::class, 'model')
            ->whereNotIn('type', $types);
    }


    public function mediaVideo()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);

    }

    public function mediaPDF()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('type', '=', 'lesson_pdf');
    }

    public function mediaAudio()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('type', '=', 'lesson_audio');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'reviewable');
    }

}
