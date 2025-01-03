<?php

namespace Botble\SimpleSlider\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SimpleSlider\Forms\SimpleSliderItemForm;
use Botble\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\SimpleSlider\Tables\SimpleSliderItemTable;  
use Illuminate\Http\Request;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseCategory;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Franchise\Models\Franchise;
use Botble\Franchise\Models\FranchiseService;



class SimpleSliderItemController extends BaseController
{
    public function index(SimpleSliderItemTable $dataTable)
    {
        return $dataTable->renderTable();
    }

    public function create()
    {
        $form = SimpleSliderItemForm::create()
            ->setUseInlineJs(true)
            ->renderForm();
        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/simple-slider::simple-slider.create_new_slide'),
                'content' => $form,
            ]);
    }

    public function store(SimpleSliderItemRequest $request)
    {
        SimpleSliderItemForm::create()->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        $form = SimpleSliderItemForm::createFromModel($simpleSliderItem)
            ->setUseInlineJs(true)
            ->renderForm();

        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/simple-slider::simple-slider.edit_slide', ['id' => $simpleSliderItem->getKey()]),
                'content' => $form,
            ]);
    }


    public function getBannerItems(Request $request)
    {
        if($request->id == 'remedies'){
            $params = Product::get();
        }else if($request->id == 'remedies_category'){
            $params = ProductCategory::get();
        }else if($request->id == 'courses'){
            $params = Course::get();
        }else if($request->id == 'course_category'){
            $params = CourseCategory::get();
        }else if($request->id == 'franchises'){
            $params = Franchise::get();
        }else{
            $params = FranchiseService::get();
        }
        return $this
            ->httpResponse()
            ->setData([
                'status' => 200,
                'getdata' => $params
            ]);
    }


    public function update(int|string $id, SimpleSliderItemRequest $request)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        SimpleSliderItemForm::createFromModel($simpleSliderItem)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        return DeleteResourceAction::make($simpleSliderItem);
    }
}
