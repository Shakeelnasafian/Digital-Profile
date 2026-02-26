<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('experience/index', [
            'experiences' => $experiences,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company'     => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['user_id'] = auth()->id();

        Experience::create($data);

        return redirect()->route('experience.index')->with('success', 'Experience added successfully.');
    }

    public function update(Request $request, string $id)
    {
        $experience = Experience::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'company'     => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $experience->update($data);

        return redirect()->route('experience.index')->with('success', 'Experience updated successfully.');
    }

    public function destroy(string $id)
    {
        $experience = Experience::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $experience->delete();

        return redirect()->route('experience.index')->with('success', 'Experience deleted successfully.');
    }
}
