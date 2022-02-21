<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoriesService;

class CategoryController extends Controller
{
    const MODEL_WITH = ['section', 'parent', 'children_recursive', 'picture'];

    function __construct()
    {
        $this->middleware('permission:categories.index|categories.create|categories.show|categories.edit|categories.destroy', ['only' => ['index']]);
        $this->middleware('permission:categories.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories.show', ['only' => ['show']]);
        $this->middleware('permission:categories.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(CategoryRequest $request, CategoriesService $service)
    {
        $categories = $service->index($request)->with(self::MODEL_WITH);

        $categories = match ($request->has('size')) {
            true => $categories->paginate($request->query('size')),
            default => $categories->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new CategoryCollection($categories))->additional($additional);
    }

    public function create(CategoriesService $service)
    {
        $category = $service->create();

        $additional = ['collections' => []];

        return (new CategoryResource($category))->additional($additional);
    }

    public function store(CategoryRequest $request, CategoriesService $service)
    {
        $category = $service->store($request);

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new CategoryResource($category->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Category $category)
    {
        $additional = ['collections' => []];

        return (new CategoryResource($category->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Category $category)
    {
        return $this->show($category);
    }

    public function update(CategoryRequest $request, Category $category, CategoriesService $service)
    {
        $category = $service->update($request, $category);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new CategoryResource($category->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Category $category, CategoriesService $service)
    {
        $category = $service->destroy($category);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new CategoryResource($category))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
