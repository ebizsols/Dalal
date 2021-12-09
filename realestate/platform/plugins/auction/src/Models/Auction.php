<?php

namespace Botble\Auction\Models;

use Botble\Base\Supports\Avatar;
use Botble\Media\Models\MediaFile;
use Botble\RealEstate\Notifications\ResetPasswordNotification;
use Exception;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use RvMedia;


class Auction extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'auctions';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'property_id',
        'title',
        'description',
        'price',
       // 'image'


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',

    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(MediaFile::class)->withDefault();
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar->url) {
            return RvMedia::url($this->avatar->url);
        }

        try {
            return (new Avatar)->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     */


    /**
     * @return string
     * @deprecated since v2.22
     */


    /**
     * @return string
     */
    // public function getNameAttribute()
    // {
    //     return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function properties()
    {
        return $this->morphMany(Property::class, 'author');
    }

    /**
     * @return bool
     */
    public function canPost(): bool
    {
        return $this->credits > 0;
    }

    /**
     * @param int $value
     * @return int
     */
    public function getCreditsAttribute($value)
    {
        return $value ?: 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    /**
     * @return BelongsToMany
     */
    // public function packages(): BelongsToMany
    // {
    //     return $this->belongsToMany(Package::class, 're_account_packages', 'account_id', 'package_id');
    // }

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
//    public function auction(){
//        return $this->hasOne(Agency::class , 'foreign_ke');
//
//    }
}
