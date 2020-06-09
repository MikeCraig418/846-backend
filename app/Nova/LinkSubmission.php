<?php

namespace App\Nova;

use App\Nova\Actions\ApproveSubmission;
use App\Nova\Actions\BulkUploadLinks;
use App\Nova\Actions\NeedsApprovers;
use App\Nova\Actions\RejectSubmission;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pdmfc\NovaFields\ActionButton;

class LinkSubmission extends Resource
{


    public static $defaultSort = ['created_at' => 'desc'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\LinkSubmission::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'submission_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'submission_title',
        'submission_media_url',
        'submission_url'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            DateTime::make('Submission Date Time', 'submission_datetime_utc')->sortable(),
            Text::make('Media Url', function () {
                $html = "<div style='padding:10px 0;'><strong>{$this->submission_title}</strong> <br/>";
                $html .= "<a href='{$this->submission_media_url}' target='_blank'>$this->submission_media_url</a> <br/>";
                $html .= "<span style='font-size:.9em' >Linked from: <a href='{$this->submission_url}' target='_blank'>External Site</a> </span></div>";

                return $html;
            })->asHtml(),
            Text::make('Link Status')->sortable(),
            Text::make('Approval Status', function () {
               return $this->approvalCountHelper($this->id);
            })->asHtml(),

            BelongsTo::make('Submitted By', 'user', User::class)->sortable()->hideFromIndex(),
            DateTime::make('Uploaded At', 'created_at')->sortable()->hideFromIndex(),
            KeyValue::make('Data'),

            HasMany::make('Approvals', 'link_submission_approvals', LinkSubmissionApproval::class)
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new BulkUploadLinks())->withMeta([
                'detachedAction' => true,
                'label' => 'Bulk Upload Links',
                'showOnIndexToolbar' => true
            ]),
            (new NeedsApprovers())->showOnTableRow()
                ->confirmText('Are you sure you want to activate this user?')
                ->confirmButtonText('Save')
                ->cancelButtonText("Cancel"),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (static::$defaultSort && empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            foreach (static::$defaultSort as $field => $order) {
                $query->orderBy($field, $order);
            }
        }

        return $query;
    }

    public function approvalCountHelper($id)
    {
        $count = \App\Models\LinkSubmission::where('id', $id)
            ->withCount([
            'link_submission_approvals_approved',
            'link_submission_approvals_rejected'
        ])
            ->first();

        $approved = $count->link_submission_approvals_approved_count ?? 0;
        $rejected = $count->link_submission_approvals_rejected_count ?? 0;
        dump($count);
        $str = '';
        if ($approved > 0) {
            $str .= "👍{$approved}";
        }
        if ($rejected > 0) {
            $str .= "👎{$rejected}";
        }
        return $str;
    }
}
