<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFulfilledJobRequest;
use App\Http\Resources\FulfilledJobResource;
use App\Models\FulfilledJob;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FulfilledJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *@param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = FulfilledJob::with(
            [
                'job',
                'job.user',
                'job.category',
                'job.user.roles',
                'bid',
                'bid.user',
                'bid.user.roles',
            ]
        )
        ->where('fulfilled', '=', ($request->query('fulfilled') === 'true'))
        ->where(function (Builder $query) {
            $query->whereHas('job.user', function (Builder $query) {
                $query->where('id', auth()->id());
            })
            ->orWhereHas('bid.user', function (Builder $query) {
                $query->where('id', auth()->id());
            });
        })
        ->paginate(5);

        return FulfilledJobResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFulfilledJobRequest  $request
     * @param \App\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFulfilledJobRequest $request, Job $job)
    {
        $this->authorize('store', [FulfilledJob::class, $job]);

        $fulfilledJob = FulfilledJob::create([
            'job_id' => $job->id,
            'bid_id' => $request->bid_id,
        ]);

        $job->unsearchable();

        return response()->json(['id' => $fulfilledJob->id], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FulfilledJob $fulfilledJob
     * @return \Illuminate\Http\Response
     */
    public function show(FulfilledJob $fulfilledJob)
    {
        $this->authorize('show', [FulfilledJob::class, $fulfilledJob]);

        return new FulfilledJobResource($fulfilledJob->load([
            'job',
            'job.user',
            'job.category',
            'job.user.roles',
            'bid',
            'bid.user',
            'bid.user.roles',
        ]));
    }
}
