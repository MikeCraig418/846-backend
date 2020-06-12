<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ApprovalStatus extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {

        if ($value == 'Needs Approvers') {
            return $query->with('link_submission_approvals_approved')->has('link_submission_approvals_approved', '<', 1);
        }

        if ($value == 'Approved') {
            return $query->with('link_submission_approvals_approved')->has('link_submission_approvals_approved', '>=', 1);
        }
        if ($value == 'Rejected') {
            return $query->with('link_submission_approvals_rejected')->has('link_submission_approvals_rejected', '>=', 1);
        }
        if ($value == 'Flag for Review') {
            return $query->with('link_submission_approvals_flagged')->has('link_submission_approvals_flagged', '>=', 1);
        }
        if ($value == 'Controversial') {
            return $query->with('link_submission_approvals_rejected')
                ->with('link_submission_approvals_approved')
                ->has('link_submission_approvals_rejected', '>=', 1)
                ->has('link_submission_approvals_approved', '>=', 1);
        }

        return $query;


    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Needs Approvers' => 'Needs Approvers',
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
            'Controversial' => 'Controversial',
            'Flag for Review' => 'Flag for Review',
            'All' => 'All',
        ];
    }

    public function default()
    {
        return 'Needs Approvers';
    }
}
