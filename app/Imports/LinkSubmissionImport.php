<?php

namespace App\Imports;

use App\Models\LinkSubmission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use N949mac\LinkSubmissionReview\Facades\LinkSubmissionReview;

class LinkSubmissionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (isset($row['submission_media_url'])) {
            $submission_media_url = $row['submission_media_url'];
        } else {
            return;
        }

        $submission_url = '';
        if (isset($row['submission_url'])) {
            $submission_url = $row['submission_url'];
        }

        $review = LinkSubmissionReview::setUrls([
            $submission_media_url,
            $submission_url,
        ])->review();

        $link_status_ref = "";
        if ($review->isDuplicate()) {
            $link_status = 'Duplicate';
            $link_status_ref = $review->getLinkStatusRef();
        } else {
            $link_status = 'First Seen';
        }

        return new LinkSubmission([
            'submission_datetime_utc' => $row['submission_datetime_utc'] ?? '1900-01-01',
            'submission_title' => $row['submission_title'] ?? '',
            'submission_url' => $submission_url,
            'submission_media_url' => $submission_media_url,
            'data' => $row,
            'user_id' => auth()->user()->id,
            'link_status' => $link_status,
            'link_status_ref' => $link_status_ref,
        ]);
    }
}
