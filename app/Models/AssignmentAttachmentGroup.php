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
 * Class AssignmentAttachment
 *
 * @package App
// * @property string $course
 * @property string $title
 * @property string $slug
 * @property string $assignment_id
 * @property string $user_id
 * @property text $full_text
 * @property string $meta_title
 */
class AssignmentAttachmentGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'full_text', 'assignment_id','user_id','meta_title', 'meta_description', 'meta_keywords'];


    public static function boot()
    {
        parent::boot();

//        static::deleting(function ($attachment) { // before delete() method call this
//            if ($attachment->isForceDeleting()) {
//                $media = $attachment->media;
//                foreach ($media as $item) {
//                    if (File::exists(public_path('/storage/uploads/' . $item->name))) {
//                        File::delete(public_path('/storage/uploads/' . $item->name));
//                    }
//                }
//                $attachment->media()->delete();
//            }
//
//        });
    }


    /**
     * Set to null if empty
     * @param $input
     */
    public function setAssignmentIdAttribute($input)
    {
        $this->attributes['assignment_id'] = $input ? $input : null;
    }

    public function setUserIdAttribute($input)
    {
        $this->attributes['user_id'] = $input ? $input : null;
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(AssignmentAttachment::class, 'group_id')->orderBy('position');
    }

}
