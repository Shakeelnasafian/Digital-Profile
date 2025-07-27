<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Project::where('user_id', auth()->user()->id)->get();

        return Inertia::render('project/index', [
            'profiles' => $profiles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $existingProfile = Project::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            // Redirect to their profile instead of showing the form again
            return redirect()->route('projects.show', $existingProfile->slug);
        }

        return Inertia::render('project/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:planned,ongoing,completed',
        ]);

        // Set the user_id to the currently authenticated user
        $data['user_id'] = auth()->id();

        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $profile = Project::where('user_id', auth()->id())->where('slug', $id)->firstOrFail();

        return Inertia::render('project/show', [
            'profile' => $profile,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $profile = Project::where('user_id', auth()->id())->where('slug', $id)->firstOrFail();

        return Inertia::render('project/edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profile = Project::where('user_id', auth()->id())->where('slug', $id)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:planned,ongoing,completed',
        ]);

        $profile->update($data);

        return redirect()->route('projects.show', $profile->slug)->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $profile = Project::where('user_id', auth()->id())->where('slug', $id)->firstOrFail();

        $profile->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
