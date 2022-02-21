<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Http\Requests\BaseFormRequest;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Services\BannersService;

class BannerController extends Controller
{
    const MODEL_WITH = ['picture'];

    function __construct()
    {
        $this->middleware('permission:banners.index|banners.create|banners.show|banners.edit|banners.destroy', ['only' => ['index']]);
        $this->middleware('permission:banners.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banners.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banners.show', ['only' => ['show']]);
        $this->middleware('permission:banners.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(BannerRequest $request, BannersService $service)
    {
        $banners = $service->index($request)->with(self::MODEL_WITH);

        $banners = match ($request->has('size')) {
            true => $banners->paginate($request->query('size')),
            default => $banners->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new BannerCollection($banners))->additional($additional);
    }

    public function create(BannersService $service)
    {
        $banner = $service->create();

        $additional = ['collections' => []];

        return (new BannerResource($banner))->additional($additional);
    }

    public function store(BannerRequest $request, BannersService $service)
    {
        $banner = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new BannerResource($banner->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Banner $banner)
    {
        $additional = ['collections' => []];

        return (new BannerResource($banner->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Banner $banner)
    {
        $additional = ['collections' => []];

        return (new BannerResource($banner->load(self::MODEL_WITH)))->additional($additional);
    }

    public function update(BannerRequest $request, Banner $banner, BannersService $service)
    {
        $banner = $service->update($request, $banner);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new BannerResource($banner->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Banner $banner, BannersService $service)
    {
        $banner = $service->destroy($banner);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new BannerResource($banner))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
