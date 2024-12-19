<?php

namespace Botble\Course\Supports;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\RepeaterFieldOption;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\Base\Supports\Helper;
use Botble\Course\Enums\OrderStatusEnum;
use Botble\Course\Enums\CourseTypeEnum;
use Botble\Course\Facades\Cart;
use Botble\Course\Facades\CourseCategoryHelper;
use Botble\Course\Forms\CourseForm;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseCategory;
use Botble\Course\Models\Review;
use Botble\Course\Repositories\Interfaces\CourseInterface;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Botble\Location\Rules\CityRule;
use Botble\Location\Rules\StateRule;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Facades\ThemeOption;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Illuminate\Validation\Rule;

class CourseHelper
{
    public function isEnabledRelatedCourses(): bool
    {
        return (bool) get_course_setting('is_enabled_related_courses', 1);
    }

    public function isZipCodeEnabled(): bool
    {
        return (bool) get_course_setting('zip_code_enabled', '0');
    }

    public function loadCountriesStatesCitiesFromPluginLocation(): bool
    {
        if (isset($this->loadLocationDataFromPluginLocation)) {
            return $this->loadLocationDataFromPluginLocation;
        }

        if (
            ! is_plugin_active('location')
            || ! Country::query()->exists()
            || ! State::query()->exists()
        ) {
            $this->loadLocationDataFromPluginLocation = false;

            return false;
        }

        $this->loadLocationDataFromPluginLocation = (bool) get_ecommerce_setting('load_countries_states_cities_from_location_plugin', 0);

        return $this->loadLocationDataFromPluginLocation;
    }

    public function isTaxEnabled(): bool
    {
        return (bool) get_ecommerce_setting('course_tax_enabled', 1);
    }


    public function couponCodeChecking($user_id, $couponcode){
        $couponcode = CourseDiscount::where('code',$couponcode)->first();
        if(isset($couponcode) && !empty($couponcode)){
            $msg = "";
            if($couponcode->type_option == "percentage"){
                $msg = "Discount ".$couponcode->value."% For all courses in category";
            }else{
                $msg = "Discount ".$couponcode->value."₹ For all courses in category";
            }
            $result = ['status'=>200, 'response'=>$couponcode,'coupon_desc'=>$msg];
            return $result;
        }else{
            return false;
        }
    }
}
