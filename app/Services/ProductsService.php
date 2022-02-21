<?php

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class ProductsService
{
    public function index(ProductRequest $request)
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('fabric'),
                AllowedFilter::exact('pattern'),
                AllowedFilter::exact('sleeve'),
                AllowedFilter::exact('fit'),
                AllowedFilter::exact('occassion'),
                AllowedFilter::exact('size'),
                AllowedFilter::scope('is_featured'),
                AllowedFilter::scope('section'),
                AllowedFilter::scope('category'),
                AllowedFilter::scope('brand'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('attributes.size'),
                'fullname',
            ]);

        // ->defaultSort('fullname');
        // ->allowedSorts(['id', 'fullname', 'price']);

        return $products;
    }

    public function create(): Product
    {
        $product = new Product();

        return $product;
    }

    public function store(ProductRequest $request): Product
    {
        DB::beginTransaction();
        try {
            $product = Product::create($request->input('data'));

            // $product->attributes()->sync($request->input('data.attributes', []));

            // $product->syncMediaMany($request->data, 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $product;
    }

    public function update(ProductRequest $request, Product $product): Product
    {
        DB::beginTransaction();
        try {
            $product->update($request->input('data'));

            // $product->attributes()->sync($request->input('data.attributes', []), true);

            // $product->syncMediaMany($request->data, 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $product;
    }

    public function destroy(Product $product): Product
    {
        DB::beginTransaction();
        try {
            $product->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $product;
    }

    public function getPrice(): Float
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),

                AllowedFilter::scope('category'),
                AllowedFilter::scope('brand'),
                AllowedFilter::scope('status'),
                'fullname',
                'slug'
            ])
            ->defaultSort('fullname')
            ->allowedSorts(['id', 'fullname', 'price']);

        return $products;

        return 1;
    }
}



/*
*

->where(function ($query) use ($request) {
                if (!!$section = $request->input('filters.section')) {
                    $section = is_array($section) ? $section : explode(',', $section);
                    $query->whereHas('section', function (Builder $query) use ($section) {
                        $query->whereIn('slug', $section);
                    });
                }
                if (!!$category = $request->input('filters.category')) {
                    $category = is_array($category) ? $category : explode(',', $category);
                    $query->orWhereHas('category', function (Builder $query) use ($category) {
                        $query->whereIn('slug', $category);
                    });
                }
            })

 */
