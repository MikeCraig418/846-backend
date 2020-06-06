<?php

namespace App\Console\Commands;

use App\Models\Evidence;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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

                $signature = "";

                $incidentLookup1 = Incident::where('state', $row['state'])
                    ->where('city', $row['city'])
                    ->where('date', $row['date'])
                    ->where('title', $row['name'])
                    ->get();

                $signature = $incidentLookup1->count() . ":";

                foreach ($row['links'] as $link) {
                    $link = str_replace('/', '\/', $link);
//                    echo '%' . $link . '%' . "\n";
                    if ($incidentLookup2 = Incident::where('links', 'like', '%' . addslashes($link) . '%')
                        ->get()) {
                        break;
                    };
                }

                $signature .= $incidentLookup2->count();

                if ($signature == "1:1") {
                    // No Change
                } elseif ($signature == "1:0") {
                    // Title  matches but links dont
                } elseif ($signature == "0:1") {

//                    echo $incidentLookup1['date'] . "\n";
//                    echo isset($row['date'] ) ? $row['date'] : '';
                    echo  "\n";
                    echo $incidentLookup1['state'] . "\n";
                    echo $row['state'] . "\n";
                    echo $incidentLookup1['city'] . "\n";
                    echo $row['city'] . "\n";
                    echo $incidentLookup1['title'] . "\n";
                    echo $row['name'] . "\n";

                    echo "=====================\n";
                } elseif ($signature == "0:0") {
                    // Probably new
                }



                continue;
                $incident = new Incident();
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
