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
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Traits\FranchiseActionsTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Botble\Franchise\Forms\FranchiseShow;
use Illuminate\Http\Request;


class FranchiseController extends BaseController
{
    use FranchiseActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/franchise::franchise.name'), route('franchise.index'));
    }

    public function index(FranchiseTable $table)
    {
        $this->pageTitle(trans('plugins/franchise::franchise.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/franchise::franchise.create'));

        return FranchiseForm::create()->renderForm();
    }

    public function store(FranchiseRequest $request)
    {
        $customer = new Customer();
        $customer->fill($request->input());
        $customer->confirmed_at = Carbon::now();
        if(!empty($request->input('password'))){
            $customer->password = Hash::make($request->input('password'));
        }else{
            $customer->password = Hash::make('123456');
        }
        $customer->dob = Carbon::parse($request->input('dob'));
        $customer->name = $request->input('franchise_name');
        $customer->save();
       
        $franchise = Franchise::create($request->input());
        $franchise->customer_id = $customer->id;
        $franchiseId = $franchise->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('franchise.index')
            ->setNextRoute('franchise.edit', $franchiseId)
            ->withCreatedSuccessMessage();
    }

    public function edit(Franchise $request, $id)
    {
        $franchise = Franchise::where('id',$id)->first();
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $franchise->franchise_name]));
        return FranchiseForm::createFromModel($franchise)->renderForm();
    }

    public function update(Franchise $franchise, FranchiseRequest $request)
    {
       $response = FranchiseForm::createFromModel($franchise)->setRequest($request)->save();
       $courseId = $response->getModel()->getKey();
        return $this
            ->httpResponse()
            ->setPreviousRoute('franchise.index')   
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Franchise $course, $id)
    {
        Franchise::where('id',$id)->delete();
        return $this
        ->httpResponse()
        ->setNextUrl(route('franchise_services.index'))
        ->withDeletedSuccessMessage();
    }


    public function show($id)
    {
        $franchise = Franchise::where('id',$id)->first();
        $customerId = $franchise->customer_id;
        $customers = Customer::where('id',$customerId)->first();
        if($customers != null){
            $customerdatas = $customers;
        }else{
            $customerdatas = '';
        }
        $this->pageTitle(trans('plugins/franchise::franchise.view', ['name' => $franchise->franchise_name ]));
        return view('plugins/franchise::franchise.show', compact('franchise','customerdatas'));
    }



    public function updateStatus(Request $request)
    {
    
        $franchise = Franchise::findOrFail($request->franchise_id);
        $franchise->application_status = $request->status;
        $franchise->save();
        return redirect()->route('franchise.show', $franchise->getKey())
            ->with('success', 'Status updated successfully!');
    }



    
}

