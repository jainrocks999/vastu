<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Requests\SelectSearchAjaxRequest;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Forms\CourseReviewForm;
use Botble\Course\Http\Requests\ReviewRequest;
use Botble\Course\Models\Customer;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseReview;
use Botble\Course\Tables\CourseReviewTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Botble\Course\Forms\TrainerForm;   //  for trainer form
use Botble\Course\Http\Requests\TrainerRequest;
use Botble\Course\Models\Trainer;

use Botble\Course\Tables\TrainerTable;

class TrainerController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::trainer.name'), route('trainer.index'));
            
    }

    
    public function index(TrainerTable $table)
    {
        $this->pageTitle(trans('plugins/course::trainer.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/course::trainer.create'));

        return TrainerForm::create()->renderForm();
    }

    public function store(TrainerRequest $request)
    {
        $form = TrainerForm::create()->setRequest($request);
        $response = $form->save();
       
        
        return $this
            ->httpResponse()
            ->setPreviousRoute('trainer.index')
            ->setNextRoute('trainer.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Trainer $trainer)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $trainer->title]));

        return TrainerForm::createFromModel($trainer)->renderForm();
    }

    public function update(Trainer $trainer, TrainerRequest $request)
    {
       $response = TrainerForm::createFromModel($trainer)->setRequest($request)->save();
  
        return $this
            ->httpResponse()
            ->setPreviousRoute('trainer.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Trainer $trainer)
    {
        return DeleteResourceAction::make($trainer);
    }

   
}
