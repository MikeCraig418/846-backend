<?php

namespace App\Models;

use App\Traits\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OptimistDigital\NovaNotesField\Traits\HasNotes;

class LinkSubmission extends Model {
	use Uuids;
	use SoftDeletes;
	use HasNotes;


	public $incrementing = false;

	protected $fillable = [
		'submission_datetime_utc',
		'submission_title',
		'submission_media_url',
		'submission_url',
		'data',
		'user_id',
		'link_status',
		'link_status_ref',
		'is_api_submission',
	];

	protected $casts = [
		'data' => 'array',
		'submission_datetime_utc' => 'date',
		'github_date' => 'date',
		'github_tags' => 'array',
	];

	protected $appends = [
		'approved_count',
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

	public function link_submission_approvals_flagged() {
		return $this->hasMany(LinkSubmissionApproval::class)->where('status', 'Flag for Review');
	}

	public function link_submission_approvals_reason() {
		return $this->hasMany(LinkSubmissionApproval::class)->where('reason', '!=', '');
	}

	public function getApprovedCountAttribute() {
//        return $this->withCount('link_submission_approvals_approved')->get();
		//        return $this->link_submission_approvals_approved()->withCount('id');
		$count = \App\Models\LinkSubmission::where('id', $this->id)
			->withCount([
				'link_submission_approvals_approved',
				'link_submission_approvals_rejected',
			])
			->first();

		$approved = $count->link_submission_approvals_approved_count ?? 0;
		$rejected = $count->link_submission_approvals_rejected_count ?? 0;

		$str = '';
		if ($approved > 0) {
			$str .= "👍{$approved}";
		}
		if ($rejected > 0) {
			$str .= "👎{$rejected}";
		}
		return $str;
	}

	public function getRejectedCountAttribute() {
		return $this->link_submission_approvals_rejected()->withCount('id');
	}
}
