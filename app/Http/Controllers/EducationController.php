<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Education;
use App\Actions\CreateEducationAction;
use App\Actions\UpdateEducationAction;
use App\Http\Requests\EducationRequest;
use App\Http\Resources\EducationResource;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::where('user_id', auth()->id())
            ->orderByDesc('start_year')
            ->get();

        return Inertia::render('education/index', [
            'educations' => EducationResource::collection($educations),
        ]);
    }

    public function store(EducationRequest $request, CreateEducationAction $action)
    {
        $action->handle($request);

        return redirect()->back()->with('success', 'Education added successfully.');
    }

    public function update(EducationRequest $request, Education $education, UpdateEducationAction $action)
    {
        $action->handle($request, $education);

        return redirect()->back()->with('success', 'Education updated successfully.');
    }

    public function destroy(Education $education)
    {
        abort_if($education->user_id !== auth()->id(), 403);

        $education->delete();

        return redirect()->back()->with('success', 'Education deleted.');
    }
}
