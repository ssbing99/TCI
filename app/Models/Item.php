<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * Class Item
 *
 * @package App
 * @property string $title
 * @property string $slug
 * @property text $description
 * @property decimal $price
 * @property decimal $discount
 * @property string $discount_type
 * @property integer $stock_count
 * @property string $item_image
 * @property tinyInteger $published
 */
class Item extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'description', 'price', 'discount', 'discount_type', 'item_image', 'stock_count', 'published', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $appends = ['image'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) { // before delete() method call this
            if ($item->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $item->item_image))) {
                    File::delete(public_path('/storage/uploads/' . $item->item_image));
                    File::delete(public_path('/storage/uploads/thumb/' . $item->item_image));
                }
            }
        });
    }

    public function getImageAttribute()
    {
        if($this->item_image != null){
            return url('storage/uploads/'.$this->item_image);
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

    public function getDiscountAttribute()
    {
        if (($this->attributes['discount'] == null)) {
            return round(0.00);
        }
        return $this->attributes['discount'];
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : round(0.00);
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setDiscountAttribute($input)
    {
        $this->attributes['discount'] = $input ? $input : round(0.00);
    }

    public function getIsAddedToCart(){
        if(auth()->check() && (auth()->user()->hasRole('student')) && (\Cart::session(auth()->user()->id)->get( $this->id))){
            return true;
        }
        return false;
    }

//    public function orderItem()
//    {
//        return $this->hasMany(OrderItem::class);
//    }

//    public function item()
//    {
//        return $this->morphMany(OrderItem::class, 'item');
//    }
}
