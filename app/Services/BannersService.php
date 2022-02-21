<?php

namespace App\Services;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class BannersService
{
    public function index(BannerRequest $request)
    {
        $banners = QueryBuilder::for(Banner::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
                'url',
            ]);

        return $banners;
    }

    public function create(): Banner
    {
        $banner = new Banner();

        return $banner;
    }

    public function store(BannerRequest $request): Banner
    {
        DB::beginTransaction();
        try {

            $banner = Banner::create($request->input('data'));

            // $banner->addMediaFromRequest($request->input('data.picture'))->toMediaCollection('pictures');

            $banner->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $banner;
    }

    public function update(BannerRequest $request, Banner $banner): Banner
    {
        DB::beginTransaction();
        try {

            $banner->update($request->input('data'));

            $banner->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $banner;
    }

    public function destroy(Banner $banner): Banner
    {
        DB::beginTransaction();
        try {

            $banner->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $banner;
    }
}
