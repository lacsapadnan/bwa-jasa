<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
    ];

    // Category Relationship
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Service Relationship
    public function service() {
        return $this->hasMany(Service::class);
    }
}
