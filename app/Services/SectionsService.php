<?php

namespace App\Services;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class SectionsService
{
    public function index(SectionRequest $request)
    {
        $sections = QueryBuilder::for(Section::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('status'),
                AllowedFilter::exact('slug'),
                'name',
            ]);

        return $sections;
    }

    public function create(): Section
    {
        $section = new Section();

        return $section;
    }

    public function store(SectionRequest $request): Section
    {
        DB::beginTransaction();
        try {

            $section = Section::create($request->input('data'));

            $section->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $section;
    }

    public function update(SectionRequest $request, Section $section): Section
    {
        DB::beginTransaction();
        try {

            $section->update($request->input('data'));

            $section->syncMediaOne($request->data, 'picture', 'pictures');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $section;
    }

    public function destroy(Section $section): Section
    {
        DB::beginTransaction();
        try {

            $section->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return $section;
    }
}
