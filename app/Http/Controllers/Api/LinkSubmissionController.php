<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkSubmission;
use App\Models\LinkSubmissionApproval;
use App\User;
use Illuminate\Http\Request;
use N949mac\LinkSubmissionReview\Facades\LinkSubmissionReview;
use N949mac\StopWords\StopWords;

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
        $flagged_count = 0;

        foreach ($request->data ?? [] as $row) {
            if (isset($row['submission_media_url'])) {
                $submission_media_url = LinkSubmissionReview::addhttp($row['submission_media_url']);
            } else {
                $errors[] = $row;
                continue;
            }

            $submission_url = '';
            if (isset($row['submission_url'])) {
                $submission_url = LinkSubmissionReview::addhttp($row['submission_url']);
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

            $linkSubmissionData = [
                'submission_datetime_utc' => $row['submission_datetime_utc'] ?? '1900-01-01',
                'submission_title' => $row['submission_title'] ?? '',
                'submission_url' => $submission_url,
                'submission_media_url' => $submission_media_url,
                'data' => $row,
                'user_id' => $user->id,
                'link_status' => $link_status,
                'link_status_ref' => $link_status_ref,
                'is_api_submission' => 1,
            ];

            $stopWordsCheck = StopWords::check($row['submission_title']);

            if ($stopWordsCheck === false || $stopWordsCheck == 'Flag') {
                $linkSubmission = LinkSubmission::create($linkSubmissionData);

                if ($stopWordsCheck == 'Flag') {

                    $linkSubmissionApproval = new LinkSubmissionApproval();
                    $linkSubmissionApproval->link_submission_id = $linkSubmission->id;
                    $linkSubmissionApproval->status = "Flag for Review";
                    $linkSubmissionApproval->reason = "";
                    $linkSubmissionApproval->user_id = $user->id;
                    $linkSubmissionApproval->save();


                    $flagged_count++;
                }

                $count++;
            } else {
                $errors[] = $row;
            }

        }

        $flaggedStr = $flagged_count > 0 ? " ($flagged_count flagged)" : "";

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully processed ' . $count . $flaggedStr . ' records',
            'dropped_records' => $errors], 200);

        exit;
    }
}
