<?php

namespace App\Services;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class BrandsService
{
    public function index(BrandRequest $request)
    {
        $brands = QueryBuilder::for(Brand::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
            ]);

        return $brands;
    }

    public function create(): Brand
    {
        $brand = new Brand();

        return $brand;
    }

    public function store(BrandRequest $request): Brand
    {
        DB::beginTransaction();
        try {

            $brand = Brand::create($request->input('data'));

            $brand->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $brand;
    }

    public function update(BrandRequest $request, Brand $brand): Brand
    {
        DB::beginTransaction();
        try {

            $brand->update($request->input('data'));

            $brand->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $brand;
    }

    public function destroy(Brand $brand): Brand
    {
        DB::beginTransaction();
        try {

            $brand->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $brand;
    }
}
