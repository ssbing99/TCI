<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Gallery
 *
 * @package App
// * @property string $course
 * @property string $user_id
 * @property string $title
 * @property text $description
 */
class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'user_id'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }


}
