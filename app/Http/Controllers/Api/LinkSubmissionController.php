<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinkSubmissionController extends Controller
{
    public function store(Request $request)
    {
        print_r($request->links);
        exit;
    }
}
