<?php

namespace App\Nova\Actions;

use App\Models\LinkSubmissionApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use OwenMelbz\RadioField\RadioButton;

class NeedsApprovers extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $linkSubmissionApproval = new LinkSubmissionApproval();
            $linkSubmissionApproval->link_submission_id = $model->id;
            $linkSubmissionApproval->status = $fields['approved?'];
            $linkSubmissionApproval->reason = $fields['optional_reason'];
            $linkSubmissionApproval->user_id = auth()->user()->id;
            $linkSubmissionApproval->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            RadioButton::make('Approved?')
                ->options([
                    'Approved' => 'Yes - Approve',
                    'Rejected' => 'No - Reject',
                    'Flag for Review' => 'Flag for Review',
                ])
                ->default('Approved') // optional
                ->stack() // optional (required to show hints)
                ->marginBetween() // optional
                ->skipTransformation() // optional
                ->toggle([  // optional
                    1 => ['max_skips', 'skip_sponsored'] // will hide max_skips and skip_sponsored when the value is 1
                ]),
            Textarea::make('Optional Reason'),
        ];
    }
}
