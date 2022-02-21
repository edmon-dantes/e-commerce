<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionCollection;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use App\Services\SectionsService;

class SectionController extends Controller
{
    const MODEL_WITH = ['categories.children', 'picture'];

    function __construct()
    {
        $this->middleware('permission:sections.index|sections.create|sections.show|sections.edit|sections.destroy', ['only' => ['index']]);
        $this->middleware('permission:sections.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sections.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sections.show', ['only' => ['show']]);
        $this->middleware('permission:sections.destroy', ['only' => ['destroy', 'destroy_multiple']]);
    }

    public function index(SectionRequest $request, SectionsService $service)
    {
        $sections = $service->index($request)->with(self::MODEL_WITH);

        $sections = match ($request->has('size')) {
            true => $sections->paginate($request->query('size')),
            default => $sections->take(500)->get()
        };

        $additional = ['collections' => []];

        return (new SectionCollection($sections))->additional($additional);
    }

    public function create(SectionsService $service)
    {
        $section = $service->create();

        $additional = ['collections' => []];

        return (new SectionResource($section))->additional($additional);
    }

    public function store(SectionRequest $request, SectionsService $service)
    {
        $section = $service->store($request);

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

    public function update(SectionRequest $request, Section $section, SectionsService $service)
    {
        $section = $service->update($request, $section);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new SectionResource($section->load(self::MODEL_WITH)))->additional($additional);
    }

    public function destroy(Section $section, SectionsService $service)
    {
        $section = $service->destroy($section);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new SectionResource($section))->additional($additional);
    }

    public function destroy_multiple(BaseFormRequest $baseFormRequest)
    {
    }
}
