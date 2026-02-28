<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Certification;
use App\Actions\CreateCertificationAction;
use App\Actions\UpdateCertificationAction;
use App\Http\Requests\CertificationRequest;
use App\Http\Resources\CertificationResource;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::where('user_id', auth()->id())
            ->orderByDesc('issue_date')
            ->get();

        return Inertia::render('certification/index', [
            'certifications' => CertificationResource::collection($certifications),
        ]);
    }

    public function store(CertificationRequest $request, CreateCertificationAction $action)
    {
        $action->handle($request);

        return redirect()->back()->with('success', 'Certification added successfully.');
    }

    public function update(CertificationRequest $request, Certification $certification, UpdateCertificationAction $action)
    {
        $action->handle($request, $certification);

        return redirect()->back()->with('success', 'Certification updated successfully.');
    }

    public function destroy(Certification $certification)
    {
        abort_if($certification->user_id !== auth()->id(), 403);

        $certification->delete();

        return redirect()->back()->with('success', 'Certification deleted.');
    }
}
