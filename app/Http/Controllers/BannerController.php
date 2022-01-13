<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Http\Requests\BaseFormRequest;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class BannerController extends Controller
{
    const MODEL_WITH = ['photo'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Banner::class)->allowedFilters([AllowedFilter::exact('id'), 'name'])->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => []];

        return (new BannerCollection($models))->additional($additional);
    }

    public function create()
    {
        $banner = new Banner();

        $additional = ['collections' => []];

        return (new BannerResource($banner))->additional($additional);
    }

    public function store(BannerRequest $request)
    {
        DB::beginTransaction();
        try {

            $banner = Banner::create($request->input('data'));

            $banner->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

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
        return $this->show($banner);
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        DB::beginTransaction();
        try {

            $banner->update($request->input('data'));

            $banner->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new BannerResource($banner->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Banner $banner)
    {
        DB::beginTransaction();
        try {

            $banner->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new BannerResource($banner))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
