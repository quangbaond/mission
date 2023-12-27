<?php

namespace App\Filament\Resources\WithDrawResource\Pages;

use App\Filament\Resources\WithDrawResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithDraw extends EditRecord
{
    protected static string $resource = WithDrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
