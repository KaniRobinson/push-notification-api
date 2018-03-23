<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Uuid;

class User extends Model
{
    // Override CRUD Methods
    public static function boot()
    {
        static::creating(function ($model) {
            $model->data = '{}';
        });
        
        parent::boot();
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
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_id',
        'app_id',
        'token',
        'active'
    ];

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',

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
    protected $table = 'users';
}
