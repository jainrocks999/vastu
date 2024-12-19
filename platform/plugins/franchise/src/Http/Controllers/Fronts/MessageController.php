<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Models\Message;
use Botble\Franchise\Tables\MessageTable;

class MessageController extends BaseController
{
    public function index(MessageTable $messageTable)
    {
        if (! FranchiseHelper::isEnabledMessagingSystem()) {
            abort(404);
        }

        $this->pageTitle(__('Messages'));

        return $messageTable->renderTable();
    }

    public function show(string $id)
    {
        if (! FranchiseHelper::isEnabledMessagingSystem()) {
            abort(404);
        }

        $message = Message::query()
            ->where('store_id', auth('customer')->user()->store->id)
            ->with(['store', 'customer'])
            ->findOrFail($id);

        $this->pageTitle(__('Viewing message #:id', ['id' => $message->getKey()]));

        return FranchiseHelper::view('vendor-dashboard.messages.show', compact('message'));
    }

    public function destroy(string $id)
    {
        if (! FranchiseHelper::isEnabledMessagingSystem()) {
            abort(404);
        }

        $message = Message::query()
            ->where('store_id', auth('customer')->user()->store->id)
            ->findOrFail($id);

        return DeleteResourceAction::make($message);
    }
}
