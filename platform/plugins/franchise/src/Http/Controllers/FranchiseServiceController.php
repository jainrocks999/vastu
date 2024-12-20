<?php

namespace Botble\Franchise\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Franchise\Forms\FranchiseServiceForm;
use Botble\Franchise\Http\Requests\FranchiseServiceRequest;
use Botble\Franchise\Models\FranchiseService;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Models\RelatedFranchise;
use Botble\Franchise\Tables\FranchiseServiceTable;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Traits\FranchiseActionsTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class FranchiseServiceController extends BaseController
{
    use FranchiseActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/franchise::franchise-service.name'), route('franchise_services.index'));
    }

    public function index(FranchiseServiceTable $table)
    {
        $this->pageTitle(trans('plugins/franchise::franchise-service.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/franchise::franchise-service.create'));

        return FranchiseServiceForm::create()->renderForm();
    }

    public function store(FranchiseServiceRequest $request)
    {
        $franServices = new FranchiseService();
        $franServices->fill($request->input());
        $franServices->save();
        // $franchiseId = $franServices->id; 
        return $this
            ->httpResponse()
            ->setPreviousRoute('franchise_services.index')
            ->setNextRoute('franchise_services.edit', $franServices)
            ->withCreatedSuccessMessage();
    }

    public function edit(FranchiseService $request, $id)
    {
        $franServices = FranchiseService::where('id',$id)->first();
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $franServices->services_name]));
        return FranchiseServiceForm::createFromModel($franServices)->renderForm();
    }

    public function update(FranchiseService $franServices, FranchiseServiceRequest $request)
    {
       $response = FranchiseServiceForm::createFromModel($franServices)->setRequest($request)->save();
        return $this
            ->httpResponse()
            ->setPreviousRoute('franchise_services.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(FranchiseService $franServices, $id)
    {
        FranchiseService::where('id',$id)->delete();
          return $this
          ->httpResponse()
          ->setNextUrl(route('franchise_services.index'))
          ->withDeletedSuccessMessage();
    }


    
}
