<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class BrandController extends Controller
{
    const MODEL_WITH = ['photo'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Brand::class)->allowedFilters([AllowedFilter::exact('id'), 'name'])->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => []];

        return (new BrandCollection($models))->additional($additional);
    }

    public function create()
    {
        $brand = new Brand();

        $additional = ['collections' => []];

        return (new BrandResource($brand))->additional($additional);
    }

    public function store(BrandRequest $request)
    {
        DB::beginTransaction();
        try {

            $brand = Brand::create($request->input('data'));

            $brand->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

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

    public function update(BrandRequest $request, Brand $brand)
    {
        DB::beginTransaction();
        try {

            $brand->update($request->input('data'));

            $brand->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new BrandResource($brand->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        try {

            $brand->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new BrandResource($brand))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
