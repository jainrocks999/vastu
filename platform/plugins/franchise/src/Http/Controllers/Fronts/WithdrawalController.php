<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Enums\WithdrawalStatusEnum;
use Botble\Franchise\Events\WithdrawalRequested;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Forms\VendorWithdrawalForm;
use Botble\Franchise\Http\Requests\Fronts\VendorEditWithdrawalRequest;
use Botble\Franchise\Http\Requests\Fronts\VendorWithdrawalRequest;
use Botble\Franchise\Models\VendorInfo;
use Botble\Franchise\Models\Withdrawal;
use Botble\Franchise\Tables\VendorWithdrawalTable;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class WithdrawalController extends BaseController
{
    public function index(VendorWithdrawalTable $table)
    {
        $this->pageTitle(__('Withdrawals'));

        return $table->renderTable();
    }

    public function create()
    {
        $user = auth('customer')->user();
        $fee = FranchiseHelper::getSetting('fee_withdrawal', 0);

        if ($user->balance <= $fee || ! $user->bank_info) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(route('franchise.vendor.withdrawals.index'))
                ->setMessage(__('Insufficient balance or no bank information'));
        }

        $this->pageTitle(__('Withdrawal request'));

        return VendorWithdrawalForm::create()->renderForm();
    }

    public function store(VendorWithdrawalRequest $request)
    {
        $fee = FranchiseHelper::getSetting('fee_withdrawal', 0);

        /**
         * @var Customer $vendor
         */
        $vendor = auth('customer')->user();
        $vendorInfo = $vendor->vendorInfo;

        if ($request->input('amount') < FranchiseHelper::getMinimumWithdrawalAmount()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('The minimum withdrawal amount is :amount', [
                    'amount' => format_price(FranchiseHelper::getMinimumWithdrawalAmount()),
                ]));
        }

        try {
            DB::beginTransaction();

            /**
             * @var Withdrawal $withdrawal
             */
            $withdrawal = Withdrawal::query()->create([
                'fee' => $fee,
                'amount' => $request->input('amount'),
                'customer_id' => $vendor->getKey(),
                'currency' => get_application_currency()->title,
                'bank_info' => $vendorInfo->bank_info,
                'description' => $request->input('description'),
                'current_balance' => $vendorInfo->balance,
                'payment_channel' => $vendorInfo->payout_payment_method,
            ]);

            $vendorInfo->balance -= $request->input('amount') + $fee;

            /**
             * @var VendorInfo $vendorInfo
             */
            $vendorInfo->save();

            event(new WithdrawalRequested($vendor, $withdrawal));

            DB::commit();
        } catch (Throwable | Exception $th) {
            DB::rollBack();

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($th->getMessage());
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('franchise.vendor.withdrawals.index'))
            ->setNextUrl(route('franchise.vendor.withdrawals.show', $withdrawal->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $withdrawal = Withdrawal::query()
            ->where([
                'id' => $id,
                'customer_id' => auth('customer')->id(),
                'status' => WithdrawalStatusEnum::PENDING,
            ])
            ->firstOrFail();

        $this->pageTitle(__('Update withdrawal request #:id', ['id' => $id]));

        return VendorWithdrawalForm::createFromModel($withdrawal)
            ->setUrl(route('franchise.vendor.withdrawals.edit', $withdrawal->getKey()))
            ->renderForm();
    }

    public function update(int|string $id, VendorEditWithdrawalRequest $request)
    {
        $withdrawal = Withdrawal::query()
            ->where([
                'id' => $id,
                'customer_id' => auth('customer')->id(),
                'status' => WithdrawalStatusEnum::PENDING,
            ])
            ->firstOrFail();

        $status = WithdrawalStatusEnum::PENDING;
        if ($request->input('cancel')) {
            $status = WithdrawalStatusEnum::CANCELED;
        }

        $withdrawal->fill([
            'status' => $status,
            'description' => $request->input('description'),
        ]);

        $withdrawal->save();

        if ($status === WithdrawalStatusEnum::CANCELED) {
            return $this
                ->httpResponse()
                ->setPreviousUrl(route('franchise.vendor.withdrawals.index'))
                ->setNextUrl(route('franchise.vendor.withdrawals.show', $withdrawal->getKey()))
                ->withUpdatedSuccessMessage();
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('franchise.vendor.withdrawals.index'))
            ->withUpdatedSuccessMessage();
    }

    public function show(int|string $id)
    {
        $withdrawal = Withdrawal::query()
            ->where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();

        $this->pageTitle(__('View withdrawal request #:id', ['id' => $id]));

        return VendorWithdrawalForm::createFromModel($withdrawal)
            ->setUrl(route('franchise.vendor.withdrawals.edit', $withdrawal->getKey()))
            ->renderForm();
    }
}
