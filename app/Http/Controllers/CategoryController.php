<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class CategoryController extends Controller
{
    const MODEL_WITH = ['section', 'parent', 'childrens', 'photo'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Category::class)->allowedFilters([AllowedFilter::exact('id'), AllowedFilter::exact('section_id'), AllowedFilter::scope('null'), 'name'])->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => []];

        return (new CategoryCollection($models))->additional($additional);
    }

    public function create()
    {
        $category = new Category();

        $additional = ['collections' => []];

        return (new CategoryResource($category))->additional($additional);
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();
        try {

            $category = Category::create($request->input('data'));

            $category->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

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

    public function update(CategoryRequest $request, Category $category)
    {
        DB::beginTransaction();
        try {

            $category->update($request->input('data'));

            $category->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new CategoryResource($category->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {

            $category->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new CategoryResource($category))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
