<?php

namespace App\Nova\Actions;

use App\Models\User;
use App\Services\DiscountService\DiscountService;
use Illuminate\Bus\Batch;
use Illuminate\Bus\PendingBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Contracts\BatchableAction;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Notifications\NovaNotification;

class SendCustomerDiscount extends Action implements ShouldQueue, BatchableAction
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(private DiscountService $discountService, private User $user) {}

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(
            fn($customer) => $this->discountService->email($customer, $fields->discount)
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Number::make('Discount')
                ->default(fn() => 10)
                ->min(1)
                ->max(100)
                ->rules('required', 'integer', 'min:1', 'max:100'),
        ];
    }

    public function withBatch(ActionFields $fields, PendingBatch $batch)
    {
        $batch->then(function (Batch $batch) use ($fields) {
            $notification = NovaNotification::make()
                ->message($fields['discount'] . '% discount sent to the following customers: ' . Arr::join($batch->resourceIds, ', ', ' and '))
                ->icon('currency-dollar')
                ->type('info');
            $this->user->notify($notification);
        });
    }

    public function withBatchId(string $batchId)
    {
        logger('Batch ID: ' . $batchId);
    }
}
