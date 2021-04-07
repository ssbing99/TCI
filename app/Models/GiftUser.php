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
 * @property text $gift_id
 * @property text $code
 * @property text $receiver_name
 * @property text $receiver_email
 */
class GiftUser extends Model
{
//    use SoftDeletes;

    protected $fillable = ['user_id', 'order_id', 'gift_id','code','receiver_name', 'receiver_email', 'notify_at'];

    protected static function boot()
    {
        parent::boot();

    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setOrderIdAttribute($input)
    {
        $this->attributes['order_id'] = $input ? $input : null;
    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function gift(){
        return $this->belongsTo(Gift::class, 'gift_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }




}
