<?php

namespace App\Models;

use App\Traits\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkSubmission extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'submission_datetime_utc',
        'submission_title',
        'submission_media_url',
        'submission_url',
        'data',
        'user_id',
        'link_status',
    ];

    protected $casts = [
        'data' => 'array',
        'submission_datetime_utc' => 'date'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function link_submission_approvals() {
        return $this->hasMany(LinkSubmissionApproval::class);
    }

    public function link_submission_approvals_approved() {
        return $this->hasMany(LinkSubmissionApproval::class)->where('status', 'Approved');
    }

    public function link_submission_approvals_rejected() {
        return $this->hasMany(LinkSubmissionApproval::class)->where('status', 'Rejected');
    }
}
