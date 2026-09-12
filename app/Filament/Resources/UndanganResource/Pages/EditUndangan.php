<?php

namespace App\Filament\Resources\UndanganResource\Pages;

use App\Filament\Resources\UndanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Override;

class EditUndangan extends EditRecord
{
    protected static string $resource = UndanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
//dipanggil filament setelah proses save selseai
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
