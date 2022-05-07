<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailUser extends Model
{
    // use HasFactory;
    use SoftDeletes;

    public $table = 'detail_user';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'users_id',
        'photo',
        'job',
        'contact_number',
        'biography',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    // User Relationship
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }

    // Experince Relationship
    public function experince()
    {
        return $this->hasMany('App\Models\Experince', 'detail_users_id');
    }
}
