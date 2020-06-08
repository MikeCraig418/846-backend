<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkSubmission extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;
}
