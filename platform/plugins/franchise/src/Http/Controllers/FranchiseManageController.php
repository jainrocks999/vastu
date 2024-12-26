<?php

namespace Botble\Franchise\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Franchise\Forms\FranchiseForm;
use Botble\Franchise\Http\Requests\FranchiseRequest;
use Botble\Franchise\Models\Franchise;
use Botble\Franchise\Models\Customer;
use Botble\Franchise\Models\RelatedFranchise;
use Botble\Franchise\Tables\FranchiseTable;
use Botble\Franchise\Tables\FranchiseManageTable;  // for franchise manage table
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Traits\FranchiseActionsTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Botble\Franchise\Forms\FranchiseShow;
use Illuminate\Http\Request;


class FranchiseManageController extends BaseController
{
    use FranchiseActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('Franchise'), route('franchise_manage.index'));
    }

    public function index(FranchiseManageTable $table)
    {
        $this->pageTitle(trans('Franchise Manage'));

        return $table->renderTable();
    }

   


    
}

