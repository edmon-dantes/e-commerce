<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandsService;

class BrandController extends Controller
{
    const MODEL_WITH = ['picture'];

    function __construct()
    {
        $this->middleware('permission:brands.index|brands.create|brands.show|brands.edit|brands.destroy', ['only' => ['index']]);
        $this->middleware('permission:brands.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:brands.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:brands.show', ['only' => ['show']]);
        $this->middleware('permission:brands.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(BrandRequest $request, BrandsService $service)
    {
        $brands = $service->index($request)->with(self::MODEL_WITH);

        $brands = match ($request->has('size')) {
            true => $brands->paginate($request->query('size')),
            default => $brands->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new BrandCollection($brands))->additional($additional);
    }

    public function create(BrandsService $service)
    {
        $brand = $service->create();

        $additional = ['collections' => []];

        return (new BrandResource($brand))->additional($additional);
    }

    public function store(BrandRequest $request, BrandsService $service)
    {
        $brand = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new BrandResource($brand->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Brand $brand)
    {
        $additional = ['collections' => []];

        return (new BrandResource($brand->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Brand $brand)
    {
        return $this->show($brand);
    }

    public function update(BrandRequest $request, Brand $brand, BrandsService $service)
    {
        $brand = $service->update($request, $brand);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new BrandResource($brand->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Brand $brand, BrandsService $service)
    {
        $brand = $service->destroy($brand);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new BrandResource($brand))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
