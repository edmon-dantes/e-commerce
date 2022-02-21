<?php

namespace App\Actions;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/* ejemplo  uso en controller
=========================
public function store(SectionRequest $request, StoreSectionAction $action) {
    $section = $action->handle($request);
    $additional = ['meta' => ['message' => 'Successfully created.']];
    return (new SectionResource($section->load(self::MODEL_WITH)))->additional($additional);
}
*/

class StoreSectionAction
{

    public function handle(SectionRequest $request): Section
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
}
