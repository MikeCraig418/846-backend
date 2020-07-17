<?php

namespace App\Observers;

use App\Models\LinkSubmission;

class LinkSubmissionObserver
{
    /**
     * Handle the link submission "created" event.
     *
     * @param  \App\LinkSubmission  $linkSubmission
     * @return void
     */
    public function created(LinkSubmission $linkSubmission)
    {
        //
    }

    /**
     * Handle the link submission "updated" event.
     *
     * @param  \App\LinkSubmission  $linkSubmission
     * @return void
     */
    public function updated(LinkSubmission $linkSubmission)
    {
        //
    }

    /**
     * Handle the link submission "deleted" event.
     *
     * @param  \App\LinkSubmission  $linkSubmission
     * @return void
     */
    public function deleted(LinkSubmission $linkSubmission)
    {
        //
    }

    /**
     * Handle the link submission "restored" event.
     *
     * @param  \App\LinkSubmission  $linkSubmission
     * @return void
     */
    public function restored(LinkSubmission $linkSubmission)
    {
        //
    }

    /**
     * Handle the link submission "force deleted" event.
     *
     * @param  \App\LinkSubmission  $linkSubmission
     * @return void
     */
    public function forceDeleted(LinkSubmission $linkSubmission)
    {
        //
    }
}
