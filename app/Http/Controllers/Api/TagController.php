<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Http\Resources\GenericResource2;
use App\Http\Resources\IncidentResource;
use App\Models\Incident;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $meta = [
        'meta' => [
            'about' => ['8 minutes and 46 seconds is the length of time associated with the killing of George Floyd,',
                'who died in police custody after police officer Derek Chauvin knelt on his neck for roughly eight minutes.',
                'This repo provides and API and archives acts of assault by public servants to American Citizens during non-violent acts of protest.'],
            'more' => [
                '• This project does not condone acts aggression of any parties',
                '• This project is meant to enable others to share their voice and stand-up against acts of violence by public servants',
                '• This project intends to fight censorship by encouraging all to get involved and mirror this data, download the media, and fight for progress',
                '• This project is not anti-police',
                '• This project is a public work dedicated to all of humanity, regardless of race, creed, or borders.',
            ],
            'get_involved' => [
                'reddit' => 'https://www.reddit.com/r/2020PoliceBrutality/',
                'collaboration' => 'https://github.com/2020PB/police-brutality',
            ]
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $incidents = Incident::select('tags')->get()->pluck('tags');

        $data = [];

        foreach($incidents as $tags) {
            foreach ($tags as $tag) {

                if (!$tag) continue;

                $data[] = $tag;
            }
        }

        sort($data);

        $data = array_values(array_unique($data));

        return response()->json(['data' => $data]);
    }

}
