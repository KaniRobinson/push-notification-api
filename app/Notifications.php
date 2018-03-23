<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Uuid;

class Notifications extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            $model->notification_id = (string)$model->generateNewId();
            $model->sound = 'default';
            $model->status = '0';
        });
        
        parent::boot();
    }

    public function generateNewId()
    {
        return Uuid::generate(4);
    }

    /**
    * Return the App Model
    *
    * @return App\App
    */
    public function app()
    {
        return $this->belongsTo('App\App', 'app_id');
    }

    /**
    * Return the User Model
    *
    * @return App\User
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'notification_id',
        'app_id',
        'user_id',
        'title',
        'body',
        'sound',
        'status',
        'action'
    ];

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
    * The attributes that are hidden.
    *
    * @var array
    */
    protected $hidden = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';
}

