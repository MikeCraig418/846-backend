<?php

namespace App\Imports;

use App\Models\LinkSubmission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

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

        $submission_media_url = trim(strtolower($this->addhttp($submission_media_url)));
        $submission_url = trim(strtolower($this->addhttp($submission_url)));

        $existingLinkSubmission = LinkSubmission::where('submission_media_url', $submission_media_url)->first();

        if ($existingLinkSubmission) {
            $link_status = 'Duplicate';
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
        ]);
    }
    public function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
}
