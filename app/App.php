<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Uuid;

class App extends Model
{
    // Override CRUD Methods
    public static function boot()
    {
        static::creating(function ($model) {
            $model->app_id = (string)$model->generateNewId();
            $model->key = (string)$model->generateUniqueKey();
            $model->data = '{}';
        });
        
        parent::boot();
    }

    public function generateUniqueKey()
    {
        return sha1(md5(time()));
    }

    public function generateNewId()
    {
        return Uuid::generate(4);
    }

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'app_id',
        'url',
        'key'
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
    protected $table = 'apps';
}
