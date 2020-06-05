<?php

namespace App\Console\Commands;

use App\Models\Incident;
use Illuminate\Console\Command;
use Spatie\Geocoder\Facades\Geocoder;

class UpdateLatLong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:update-lat-long';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the lat/lon columns for Incidents';

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
        foreach (Incident::all() as $incident) {

            if ($incident->lat != "" && $incident->lon != "") continue;

            $response = Geocoder::getCoordinatesForAddress("{$incident->city}, {$incident->state}");;
            $incident->lat = $response['lat'];
            $incident->long = $response['lng'];
            $incident->save();

        }
    }
}
