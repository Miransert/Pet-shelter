<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * @property int id
 * @property string name
 * @property string description
 * @property string image_path
 * @property int|null adopted_by
 */
class Adoption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_path'];

    public function scopeUnadopted(Builder $query)
    {
        return $query->whereNull('adopted_by');
    }
}
