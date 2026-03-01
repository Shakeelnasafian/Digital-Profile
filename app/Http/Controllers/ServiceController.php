<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use App\Actions\CreateServiceAction;
use App\Actions\UpdateServiceAction;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('user_id', auth()->id())
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('services/index', [
            'services' => ServiceResource::collection($services),
        ]);
    }

    public function store(ServiceRequest $request, CreateServiceAction $action)
    {
        $action->execute($request);

        return redirect()->back()->with('success', 'Service added successfully.');
    }

    public function update(ServiceRequest $request, Service $service, UpdateServiceAction $action)
    {
        $action->execute($request, $service);

        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $service->delete();

        return redirect()->back()->with('success', 'Service deleted.');
    }
}
