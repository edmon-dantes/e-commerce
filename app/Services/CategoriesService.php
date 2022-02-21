<?php

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class CategoriesService
{
    public function index(CategoryRequest $request)
    {
        $categories = QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('section_id'),
                AllowedFilter::scope('null'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
            ]);

        return $categories;
    }

    public function create(): Category
    {
        $category = new Category();

        return $category;
    }

    public function store(CategoryRequest $request): Category
    {
        DB::beginTransaction();
        try {

            $category = Category::create($request->input('data'));

            $category->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $category;
    }

    public function update(CategoryRequest $request, Category $category): Category
    {
        DB::beginTransaction();
        try {

            $category->update($request->input('data'));

            $category->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $category;
    }

    public function destroy(Category $category): Category
    {
        DB::beginTransaction();
        try {

            $category->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $category;
    }
}
