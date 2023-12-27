<?php

namespace App\Filament\Resources\UserMissionResource\Pages;

use App\Filament\Resources\UserMissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserMission extends EditRecord
{
    protected static string $resource = UserMissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
