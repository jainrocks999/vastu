<?php

namespace Botble\Course\Http\Resources;

use Botble\Course\Models\Course;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Product
 */
class AvailableCourseResource extends JsonResource
{
    public function toArray($request): array
    {
        $name = $this->name;
        $taxPrice = $this->front_sale_price * $this->total_taxes_percentage / 100;
        return [
            // 'id' => $this->id,
            // 'name' => $name,
            // 'sku' => $this->sku,
            // 'description' => $this->description,
            // 'slug' => $this->slug,
            // 'with_storehouse_management' => $this->with_storehouse_management,
            // 'quantity' => $this->quantity,
            // 'is_out_of_stock' => $this->isOutOfStock(),
            // 'stock_status_label' => $this->stock_status_label,
            // 'stock_status_html' => $this->stock_status_html,
            // 'price' => $this->front_sale_price,
            // 'formatted_price' => format_price($this->front_sale_price),
            // 'final_price' => $this->front_sale_price + $taxPrice,
            // 'original_price' => $this->original_price,
            // 'tax_price' => $taxPrice,
            // 'total_taxes_percentage' => $this->total_taxes_percentage,
            // 'image_with_sizes' => $this->image_with_sizes,
            // 'weight' => $this->weight,
            // 'height' => $this->height,
            // 'wide' => $this->wide,
            // 'length' => $this->length,
            // 'product_link' => Auth::user()->hasPermission('products.edit') ? route('products.edit', $this->original_product->id) : '',
            // 'image_url' => RvMedia::getImageUrl($this->image, 'thumb', false, RvMedia::getDefaultImage()),
            // 'is_variation' => $this->is_variation,
            // 'variations' => $this->variations->map(function ($item) {
            //     return (new self($item->product));
            // }),
            // 'product_options' => ProductOptionResource::collection($this->is_variation ? $this->original_product->options : $this->options),
            // $this->mergeWhen($this->is_variation, function () {
            //     return [
            //         'variation_attributes' => $this->variation_attributes,
            //     ];
            // }),
            // 'original_product_id' => $this->original_product->id,
        
            'title' => $this->title,
            'code' => $this->code,
            'label1' => $this->label1,
            'description1' => $this->description1,
            'label2' => $this->label2,
            'description2' => $this->description2,
            'label3' => $this->label3,
            'description3' => $this->description3,
            'label4' => $this->label4,
            'description4' => $this->description4,
            'image' => $this->image,
            'video' => $this->video,
            'docs' => $this->docs,
            'type' => $this->type,
            'price' => $this->price,
            'instructor' => $this->instructor,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'language' => $this->language,
            'prerequisites' => $this->prerequisites,
            'enrollment_limit' => $this->enrollment_limit,
            'enrollment_type' => $this->enrollment_type,
            'certification' => $this->certification,
            'discounts' => $this->discounts,
            'tags' => $this->tags,
            'keywords' => $this->keywords,
            'course_category_id' => $this->course_category_id,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
    }
}
