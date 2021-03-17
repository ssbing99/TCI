<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $guarded = [];

    protected $appends = ['image'];

    public function getImageAttribute($size = false){
        if (! $size) {
            $size = config('gravatar.default.size');
        }

        // fake email
        $email = substr(preg_replace('#\<(.*?)\>#', '', str_replace(' ', '', $this->name)), 0,5).'@gmail.com';

        return gravatar()->get($email, ['size' => $size]);
    }
}
