<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-all');
    }

    public function index()
    {
        $services = Service::with('institution')->get(); 
        return ServiceResource::collection($services);
    }

   
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());
        return new ServiceResource($service->load('institution')); 
    }

   
    public function show(Service $service)
    {
        return new ServiceResource($service->load('institution')); 
    }

    
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        return new ServiceResource($service->load('institution')); 
    }

    
}