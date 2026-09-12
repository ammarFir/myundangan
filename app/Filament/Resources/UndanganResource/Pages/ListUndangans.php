<?php

namespace App\Filament\Resources\UndanganResource\Pages;

use App\Filament\Resources\UndanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUndangans extends ListRecords
{
    protected static string $resource = UndanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
