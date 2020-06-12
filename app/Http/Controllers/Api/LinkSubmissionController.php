<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkSubmission;
use App\User;
use Illuminate\Http\Request;
use N949mac\LinkSubmissionReview\Facades\LinkSubmissionReview;

class LinkSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $apiToken = $request->header('Api-Token');

        if (!$apiToken) {
            return response()->json([
                'message' => 'Forbidden. You must include your api token in the Api-Token header'], 403);

        }

        $user = User::where('api_token', '=', $apiToken)->first();

        if (!$user) {

            return response()->json([
                'message' => 'Forbidden. Unauthorized token.'], 403);
        }

        $errors = [];
        $count = 0;

        foreach ($request->data ?? [] as $row) {
            if (isset($row['submission_media_url'])) {
                $submission_media_url = $row['submission_media_url'];
            } else {
                $errors[] = $row;
                continue;
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

            LinkSubmission::create([
                'submission_datetime_utc' => $row['submission_datetime_utc'] ?? '1900-01-01',
                'submission_title' => $row['submission_title'] ?? '',
                'submission_url' => $submission_url,
                'submission_media_url' => $submission_media_url,
                'data' => $row,
                'user_id' => $user->id,
                'link_status' => $link_status,
                'link_status_ref' => $link_status_ref,
                'is_api_submission' => 1,
            ]);
            $count++;
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully processed ' . $count . ' records',
            'dropped_records' => $errors], 200);

        exit;
    }
}
