<?php

namespace App\Filament\Resources\IntroduceResource\Pages;

use App\Filament\Resources\IntroduceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntroduces extends ListRecords
{
    protected static string $resource = IntroduceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
