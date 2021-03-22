<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Support\Facades\File;
use Mtownsend\ReadTime\ReadTime;


/**
 * Class Lesson
 *
 * @package App
// * @property string $course
 * @property string $title
 * @property text $description
 * @property integer $attachment_id
 * @property integer $user_id
 */
class Critique extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'attachment_id','user_id'];


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($critique) { // before delete() method call this
            if ($critique->isForceDeleting()) {
                $media = $critique->media;
                foreach ($media as $item) {
                    if (File::exists(public_path('/storage/uploads/' . $item->name))) {
                        File::delete(public_path('/storage/uploads/' . $item->name));
                    }
                }
                $critique->media()->delete();
            }

        });
    }


    /**
     * Set to null if empty
     * @param $input
     */
    public function setAttachmentIdAttribute($input)
    {
        $this->attributes['attachment_id'] = $input ? $input : null;
    }

    public function setUserIdAttribute($input)
    {
        $this->attributes['user_id'] = $input ? $input : null;
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'model');
    }
}
