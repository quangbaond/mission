<?php

namespace App\Filament\Resources\UserMissionResource\Pages;

use App\Filament\Resources\UserMissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserMissions extends ListRecords
{
    protected static string $resource = UserMissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
