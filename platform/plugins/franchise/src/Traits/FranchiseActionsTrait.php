<?php

namespace Botble\Franchise\Traits;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Franchise\Http\Requests\SearchCourseAndVariationsRequest;
use Botble\Franchise\Http\Resources\AvailableCourseResource;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Models\Franchise;
use Botble\Franchise\Services\HandleApplyCouponService;
use Botble\Franchise\Services\HandleCourseOrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

trait FranchiseActionsTrait
{


    public function getRelationBoxes(int|string|null $id, BaseHttpResponse $response): BaseHttpResponse
    {
        if (! CourseHelper::isEnabledRelatedCourses()) {
            return $response->setData('');
        }

        $course = null;

        if ($id) {
            $with = [];

            if (CourseHelper::isEnabledRelatedCourses()) {
                $with[] = 'courses';
            }

            $course = Course::query()->with($with)->find($id);
        }

        $dataUrl = route(
            'courses.get-list-course-for-search',
            ['course_id' => $course?->getKey()]
        );

        return $response->setData(
            view(
                'plugins/course::courses.partials.extras',
                compact('course', 'dataUrl')
            )->render()
        );
    }



    //Get Product detail
    public function getCourseDetails(int|string|null $id, BaseHttpResponse $response): BaseHttpResponse
    {

        $course = null;
        if ($id) {
            $course = Course::query()->with($with)->find($id);
        }

        $dataUrl = route(
            'courses.get-list-course-for-search',
            ['course_id' => $course?->getKey()]
        );

        return $response->setData(
            view(
                'plugins/course::courses.partials.extras',
                compact('course', 'dataUrl')
            )->render()
        );
    }

    //Course Selected list
    public function getListCourseForSelect(CourseListRequest $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $keyword = $request->input('keyword');

        $includeVariation = $request->input('include_variation');

        $availableCourses = Course::query()
            ->when(! Auth::check(), function ($query) {
                $query->wherePublished();
            })
            ->where('is_variation', '<>', 1)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $keyword = '%' . trim($keyword) . '%';

                    $query
                        ->where('name', 'LIKE', $keyword)
                        ->orWhere('sku', 'LIKE', $keyword)
                        ->orWhereHas('variations.product', function ($query) use ($keyword) {
                            $query->where('sku', 'LIKE', $keyword);
                        });
                });
            })
            ->select([
                'ec_products.*',
            ])
            ->distinct('ec_products.id')
            ->when($includeVariation, function ($query) {
                $query
                    ->join(
                        'ec_product_variations',
                        'ec_product_variations.configurable_product_id',
                        '=',
                        'ec_products.id'
                    )
                    ->join(
                        'ec_product_variation_items',
                        'ec_product_variation_items.variation_id',
                        '=',
                        'ec_product_variations.id'
                    );
            })
            ->simplePaginate(10);

        foreach ($availableCourses as &$availableCourse) {
            $image = Arr::first($availableCourse->images) ?? null;
            $availableCourse->image_url = RvMedia::getImageUrl($image, 'thumb', false, RvMedia::getDefaultImage());
            $availableCourse->price = $availableCourse->front_sale_price;
            if ($includeVariation) {
                foreach ($availableCourse->variations as &$variation) {
                    $variation->price = $variation->product->front_sale_price;
                    foreach ($variation->variationItems as &$variationItem) {
                        $variationItem->attribute_title = $variationItem->attribute->title;
                    }
                }
            }
        }

        return $response->setData($availableCourses);
    }


    public function getAllCourseAndVariations(
        SearchCourseAndVariationsRequest $request,
        BaseHttpResponse $response
    ): BaseHttpResponse {
        $selectedCourses = collect();

        if ($courseIds = $request->input('course_ids', [])) {
            $selectedCourses = Course::query()
                ->whereIn('id', $courseIds)
                ->with(['variationInfo.configurableCourse'])
                ->get();
        }

        $keyword = $request->input('keyword');

        $availableCourses = Course::query()
            ->select(['course.*'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $keyword = '%' . trim($keyword) . '%';
                    $query
                        ->where('title', 'LIKE', $keyword)
                        ->orWhere('code', 'LIKE', $keyword);
                });
            });

        $availableCourses = $availableCourses->simplePaginate(10);

        return $response->setData(AvailableCourseResource::collection($availableCourses)->response()->getData());
    }


    public function checkCouponCode(Request $request, HandleApplyCouponService $handleApplyCouponService){
        $course = Course::find($request->course_id);
        if($course){
            $result = $handleApplyCouponService->applyCouponWhenCreatingOrderFromStudent($request, $course);
            if (isset($result['error']) && $result['error']) {
                $response = ['status'=>400,'msg' => $result['message']];
                return response($response, 200);
            }
            return [
                'status'=>200,
                'data' => Arr::get($result, 'data', []),
                'msg' => trans('plugins/course::course-order.applied_coupon_success',['code' => $request->input('coupon_code')])
            ];
        }else{
            return ['status'=>404,'msg'=>"Course not found."];
        }
    }
}
