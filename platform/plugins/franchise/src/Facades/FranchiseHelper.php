<?php

namespace Botble\Franchise\Facades;

use Botble\Franchise\Supports\FranchiseHelper as FranchiseHelperSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed view(string $view, array $data = [])
 * @method static string viewPath(string $view, bool $checkViewExists = true)
 * @method static array|string|int|bool|null getSetting(string $key, array|string|int|bool|null $default = '')
 * @method static string getSettingKey(string $key = '')
 * @method static array discountTypes()
 * @method static string getAssetVersion()
 * @method static bool hideStorePhoneNumber()
 * @method static bool hideStoreEmail()
 * @method static bool hideStoreSocialLinks()
 * @method static bool hideStoreAddress()
 * @method static bool allowVendorManageShipping()
 * @method static mixed sendMailToVendorAfterProcessingOrder($orders)
 * @method static \Botble\Base\Supports\EmailHandler setEmailVendorVariables(\Botble\Ecommerce\Models\Order $order)
 * @method static bool isCommissionCategoryFeeBasedEnabled()
 * @method static float maxFilesizeUploadByVendor()
 * @method static int maxProductImagesUploadByVendor()
 * @method static bool isVendorRegistrationEnabled()
 * @method static float getMinimumWithdrawalAmount()
 * @method static bool allowVendorDeleteTheirOrders()
 * @method static bool isEnabledMessagingSystem()
 * @method static array getAllowedSocialLinks()
 * @method static bool isSingleVendorCheckout()
 *
 * @see \Botble\Franchise\Supports\FranchiseHelper
 */
class FranchiseHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FranchiseHelperSupport::class;
    }
}