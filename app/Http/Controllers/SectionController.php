<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionCollection;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class SectionController extends Controller
{
    const MODEL_WITH = ['categories.childrens', 'photo'];

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Section::class)->allowedFilters([AllowedFilter::exact('id'), 'name'])->with(self::MODEL_WITH);

        // if (!(auth()->check() && auth()->user()->hasRole('super-admin'))) {
        //     $models->where('status', 1);
        // }

        $models = match ($request->has('size')) {
            true => $models->paginate($request->query('size')),
            default => $models->take(20)->get()
        };

        $additional = ['collections' => []];

        return (new SectionCollection($models))->additional($additional);
    }

    public function create()
    {
        $section = new Section();

        $additional = ['collections' => []];

        return (new SectionResource($section))->additional($additional);
    }

    public function store(SectionRequest $request)
    {
        DB::beginTransaction();
        try {

            $section = Section::create($request->input('data'));

            $section->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new SectionResource($section->load(self::MODEL_WITH)))->additional($additional);
    }

    public function show(Section $section)
    {
        $additional = ['collections' => []];

        return (new SectionResource($section->load(self::MODEL_WITH)))->additional($additional);
    }

    public function edit(Section $section)
    {
        return $this->show($section);
    }

    public function update(SectionRequest $request, Section $section)
    {
        DB::beginTransaction();
        try {

            $section->update($request->input('data'));

            $section->syncMediaOne($request->data, 'photo', 'photos');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new SectionResource($section->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Section $section)
    {
        DB::beginTransaction();
        try {

            $section->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new SectionResource($section))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
