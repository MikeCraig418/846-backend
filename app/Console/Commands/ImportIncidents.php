<?php

namespace App\Console\Commands;

use App\Models\Evidence;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportIncidents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:import-incidents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import incidents from 2020PB Repo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source_url = "https://raw.githubusercontent.com/2020PB/police-brutality/data_build/all-locations.json";

        $response = Http::get($source_url);

        if ($incidents = $response->json()) {

            foreach ($incidents['data'] as $row) {

                if (!isset($row['name'])) {
                    echo "--- missing data ---";
                    print_r($row);
                    continue;
                }
                $incident = new Incident();
                $incident->pb_id = $row['id'] ?? 'no-id-' . Str::random(8);
                $incident->state = $row['state'] ?? '';
                $incident->city = $row['city'] ?? '';
                $incident->title = $row['name'];
                $incident->date = ($this->isValidForCarbon($row['date'])) ? $row['date'] : '1900-01-01';
                $incident->links = $row['links'];
                $incident->lat = 0;
                $incident->long = 0;

                $incident->save();

                foreach ($row['links'] as $link) {
                    $evidence = new Evidence();
                    $evidence->url = $link;
                    $evidence->incident_id = $incident->id;
                    $evidence->save();
                }

            }


        }
    }

    function isValidForCarbon($date)
    {
        if (!$date) return false;

        return new Carbon($date) instanceof Carbon;
    }

}
