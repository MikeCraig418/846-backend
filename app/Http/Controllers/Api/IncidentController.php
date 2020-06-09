<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncidentResource;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
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

        $filterBy = $request->filter ?? [];

        $incidents = Incident::select('*');

        foreach ($filterBy as $filter => $value) {
            $incidents = $incidents->where($filter, $value);
        }

        if ($request->include == 'evidence') {
            $incidents = $incidents->with('evidence.video');
        }

        $incidents = $incidents->get();


        return IncidentResource::collection($incidents)->additional($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Incident $incident
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        if ($request->include == 'evidence') {
            $incident = Incident::where('id', $id)->orWhere('pb_id', $id)->with('evidence.video')->get();
        } else {
            $incident = Incident::where('id', $id)->orWhere('pb_id', $id)->get();
        }

        return (new IncidentResource($incident))->additional($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Incident $incident
     * @return \Illuminate\Http\Response
     */
    public function edit(Incident $incident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Incident $incident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Incident $incident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incident $incident)
    {
        //
    }
}
