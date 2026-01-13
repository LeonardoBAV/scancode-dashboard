<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders\Pages;

use App\Filament\Dashboard\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->editAction(),
            $this->pendingAction(),
            $this->completeAction(),
            $this->cancelAction(),
        ];
    }

    public function editAction(): EditAction
    {
        return EditAction::make()
            ->visible(fn (Order $record): bool => $record->canBeUpdated())
            ->icon('phosphor-note-pencil')
            ->slideOver();
    }

    public function pendingAction(): Action
    {
        return Action::make('pending')
            ->label(__('resources.order.actions.pending'))
            ->visible(fn (Order $record): bool => ! $record->isPending())
            ->color('primary')
            ->icon('phosphor-file-dashed')
            ->action(fn (Order $record) => $record->toPending());
    }

    public function completeAction(): Action
    {
        return Action::make('complete')
            ->label(__('resources.order.actions.complete'))
            ->visible(fn (Order $record): bool => ! $record->isCompleted())
            ->color('secondary')
            ->icon('phosphor-list-checks')
            ->action(fn (Order $record) => $record->toComplete());
    }

    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->label(__('resources.order.actions.cancel'))
            ->visible(fn (Order $record): bool => ! $record->isCancelled())
            ->color('danger')
            ->icon('phosphor-receipt-x')
            ->action(fn (Order $record) => $record->toCancel());
    }
}
