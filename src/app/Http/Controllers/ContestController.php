<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContestRequest;
use App\Services\ContestService;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function __construct(
        private ContestService $contestService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages::contest.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateContestRequest $request)
    {
        $this->contestService->create($request->getDTO());
        
        return redirect()->route('contest.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
