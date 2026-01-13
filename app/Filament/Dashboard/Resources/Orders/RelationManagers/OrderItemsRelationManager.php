<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders\RelationManagers;

use App\Filament\Dashboard\Resources\OrderItems\OrderItemResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $relatedResource = OrderItemResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()->slideOver()->visible(fn (): bool => $this->getOwnerRecord()->canBeUpdated()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn (): bool => $this->getOwnerRecord()->canBeDeleted()),
                ]),
            ]);
    }
}
