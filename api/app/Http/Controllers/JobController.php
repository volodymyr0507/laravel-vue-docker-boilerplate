<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        Job::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'vehicle' => $request->vehicle,
            'user_id' => auth()->id()
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        if ($job->fulfilledJob()->exists()) {
            throw new ModelNotFoundException();
        }

        $this->authorize('show', [Job::class, $job]);

        return new JobResource($job->load(['user', 'category', 'user.roles']));
    }
}
