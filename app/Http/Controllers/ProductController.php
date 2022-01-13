<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

const FABRIC_ARRAY = ['Cotton', 'Polyster', 'Wool'];
const SLEEVE_ARRAY = ['Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'SleeveLess'];
const PATTERN_ARRAY = ['Checked', 'Plain', 'Printed', 'Self', 'Solid'];
const FIT_ARRAY = ['Regular', 'Slim'];
const OCCASSION_ARRAY = ['Casual', 'Formal'];
const SIZE_ARRAY = ['Small', 'Medium', 'Large'];

class ProductController extends Controller
{
    const MODEL_WITH = ['section', 'category', 'brand', 'attributes', 'photos'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Product::class)
            ->allowedFilters([AllowedFilter::exact('id'), AllowedFilter::scope('is_featured'), AllowedFilter::scope('status'), 'fullname'])
            ->defaultSort('fullname')
            ->allowedSorts(['id', 'fullname', 'price'])
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
            ->where(function ($query) use ($request) {
                if (!!$brand = $request->input('filters.brand')) {
                    $brand = is_array($brand) ? $brand : explode(',', $brand);
                    $query->whereHas('brand', function (Builder $query) use ($brand) {
                        $query->whereIn('slug', $brand);
                    });
                }
            })
            ->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => ['fabric' => FABRIC_ARRAY, 'sleeve' => SLEEVE_ARRAY, 'pattern' => PATTERN_ARRAY, 'fit' => FIT_ARRAY, 'occassion' => OCCASSION_ARRAY, 'size' => SIZE_ARRAY]];

        return (new ProductCollection($models))->additional($additional);
    }

    public function create()
    {
        $product = new Product();

        $additional = ['collections' => ['fabric' => FABRIC_ARRAY, 'sleeve' => SLEEVE_ARRAY, 'pattern' => PATTERN_ARRAY, 'fit' => FIT_ARRAY, 'occassion' => OCCASSION_ARRAY, 'size' => SIZE_ARRAY]];

        return (new ProductResource($product))->additional($additional);
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {

            $product = Product::create($request->input('data'));

            $product->attributes()->sync($request->input('data.attributes', []));

            $product->syncMediaMany($request->data, 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Product $product)
    {
        $additional = ['collections' => ['fabric' => FABRIC_ARRAY, 'sleeve' => SLEEVE_ARRAY, 'pattern' => PATTERN_ARRAY, 'fit' => FIT_ARRAY, 'occassion' => OCCASSION_ARRAY, 'size' => SIZE_ARRAY]];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Product $product)
    {
        return $this->show($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->update($request->input('data'));

            $product->attributes()->sync($request->input('data.attributes', []), true);

            $product->syncMediaMany($request->data, 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {

            $product->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new ProductResource($product))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
