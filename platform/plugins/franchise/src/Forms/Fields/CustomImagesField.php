<?php

namespace Botble\Franchise\Forms\Fields;

use Botble\Base\Forms\FormField;
use Botble\Franchise\Facades\FranchiseHelper;

class CustomImagesField extends FormField
{
    protected function getTemplate(): string
    {
        return FranchiseHelper::viewPath('vendor-dashboard.forms.fields.custom-images');
    }
}
