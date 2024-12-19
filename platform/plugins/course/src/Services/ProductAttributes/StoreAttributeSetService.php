<?php

namespace Botble\Course\Services\CourseAttributes;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Course\Models\CourseAttribute;
use Botble\Course\Models\CourseAttributeSet;
use Illuminate\Http\Request;

class StoreAttributeSetService
{
    public function execute(Request $request, CourseAttributeSet $courseAttributeSet): CourseAttributeSet
    {
        $existing = $courseAttributeSet->exists;

        $data = $request->input();

        $courseAttributeSet->fill($data);
        $courseAttributeSet->save();

        if (! $existing) {
            event(new CreatedContentEvent(PRODUCT_ATTRIBUTE_SETS_MODULE_SCREEN_NAME, $request, $courseAttributeSet));
        } else {
            event(new UpdatedContentEvent(PRODUCT_ATTRIBUTE_SETS_MODULE_SCREEN_NAME, $request, $courseAttributeSet));
        }

        $attributes = json_decode($request->input('attributes', '[]'), true) ?: [];
        $deletedAttributes = json_decode($request->input('deleted_attributes', '[]'), true) ?: [];

        $this->deleteAttributes($courseAttributeSet->getKey(), $deletedAttributes);
        $this->storeAttributes($courseAttributeSet->getKey(), $attributes);

        return $courseAttributeSet;
    }

    protected function deleteAttributes(int|string $courseAttributeSetId, array $attributeIds): void
    {
        foreach ($attributeIds as $id) {
            $attribute = CourseAttribute::query()->where([
                'id' => $id,
                'attribute_set_id' => $courseAttributeSetId,
            ])->first();

            if ($attribute) {
                $attribute->delete();
                event(new DeletedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
            }
        }
    }

    protected function storeAttributes(int|string $courseAttributeSetId, array $attributes): void
    {
        foreach ($attributes as $item) {
            if (isset($item['id'])) {
                $attribute = CourseAttribute::query()->find($item['id']);
                if (! $attribute) {
                    $item['attribute_set_id'] = $courseAttributeSetId;
                    $attribute = CourseAttribute::query()->create($item);

                    event(new CreatedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
                } else {
                    $attribute->fill($item);
                    $attribute->save();

                    event(new UpdatedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
                }
            }
        }
    }
}
