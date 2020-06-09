<?php

namespace App\Policies;

use App\Models\LinkSubmissionApproval;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkSubmissionApprovalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view link submissions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\LinkSubmissionApproval  $linkSubmissionApproval
     * @return mixed
     */
    public function view(User $user, LinkSubmissionApproval $linkSubmissionApproval)
    {

        return $user->hasPermissionTo('view link submissions');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\LinkSubmissionApproval  $linkSubmissionApproval
     * @return mixed
     */
    public function update(User $user, LinkSubmissionApproval $linkSubmissionApproval)
    {
        return $linkSubmissionApproval->user_id === $user->id || $user->isAdmin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\LinkSubmissionApproval  $linkSubmissionApproval
     * @return mixed
     */
    public function delete(User $user, LinkSubmissionApproval $linkSubmissionApproval)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\LinkSubmissionApproval  $linkSubmissionApproval
     * @return mixed
     */
    public function restore(User $user, LinkSubmissionApproval $linkSubmissionApproval)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\LinkSubmissionApproval  $linkSubmissionApproval
     * @return mixed
     */
    public function forceDelete(User $user, LinkSubmissionApproval $linkSubmissionApproval)
    {
        //
    }
}
