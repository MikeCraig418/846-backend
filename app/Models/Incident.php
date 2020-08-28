<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $appends = [
        'geocoding'
    ];

    protected $fillable = [
        'pb_id',
        'state',
        'city',
        'title',
        'date',
        'links',
        'lat',
        'long',
        'tags',
    ];

    protected $hidden = [
        'link_1',
        'link_2',
        'link_3',
        'link_4',
        'link_5',
        'link_6',
        'link_7',
        'lat',
        'long',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'date' => 'date',
        'links' => 'array',
        'tags' => 'array',
    ];

    public function evidence()
    {
        return $this->hasMany('App\Models\Evidence');
    }

    public function getGeocodingAttribute()
    {
        return ['lat' => $this->lat, 'long' => $this->long];
    }
}
