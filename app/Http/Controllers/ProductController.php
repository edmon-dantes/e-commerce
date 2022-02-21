<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductsService;

const FABRIC_ARRAY = ['Cotton', 'Polyster', 'Wool'];
const SLEEVE_ARRAY = ['Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'SleeveLess'];
const PATTERN_ARRAY = ['Checked', 'Plain', 'Printed', 'Self', 'Solid'];
const FIT_ARRAY = ['Regular', 'Slim'];
const OCCASSION_ARRAY = ['Casual', 'Formal'];
const SIZE_ARRAY = ['Small', 'Medium', 'Large'];
const OPTION_TYPE_ARRAY = ['Select', 'Radio'];

class ProductController extends Controller
{
    const MODEL_WITH = ['category', 'brand', 'pictures'];

    function __construct()
    {
        $this->middleware('permission:products.index|products.create|products.show|products.edit|products.destroy', ['only' => ['index']]);
        $this->middleware('permission:products.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products.show', ['only' => ['show']]);
        $this->middleware('permission:products.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(ProductRequest $request, ProductsService $service)
    {
        $products = $service->index($request)->with(self::MODEL_WITH);

        $products = match ($request->has('size')) {
            true => $products->paginate($request->query('size')),
            default => $products->take(500)->get()
        };

        $additional = ['collections' => ['fabric' => FABRIC_ARRAY, 'sleeve' => SLEEVE_ARRAY, 'pattern' => PATTERN_ARRAY, 'fit' => FIT_ARRAY, 'occassion' => OCCASSION_ARRAY, 'size' => SIZE_ARRAY]];

        return (new ProductCollection($products))->additional($additional);
    }

    public function create(ProductsService $service)
    {
        $product = $service->create();

        $additional = ['collections' => ['fabric' => FABRIC_ARRAY, 'sleeve' => SLEEVE_ARRAY, 'pattern' => PATTERN_ARRAY, 'fit' => FIT_ARRAY, 'occassion' => OCCASSION_ARRAY, 'size' => SIZE_ARRAY]];

        return (new ProductResource($product))->additional($additional);
    }

    public function store(ProductRequest $request, ProductsService $service)
    {
        $product = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Product $product)
    {
        $additional = [
            'collections' => [
                'fabric' => FABRIC_ARRAY,
                'sleeve' => SLEEVE_ARRAY,
                'pattern' => PATTERN_ARRAY,
                'fit' => FIT_ARRAY,
                'occassion' => OCCASSION_ARRAY,
                'size' => SIZE_ARRAY
            ]
        ];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Product $product)
    {
        return $this->show($product);
    }

    public function update(ProductRequest $request, Product $product, ProductsService $service)
    {
        $product = $service->update($request, $product);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new ProductResource($product->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Product $product, ProductsService $service)
    {
        $product = $service->destroy($product);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new ProductResource($product))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
