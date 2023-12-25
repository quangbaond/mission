<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MissionResource\Pages;
use App\Filament\Resources\MissionResource\RelationManagers;
use App\Models\Mission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MissionResource extends Resource
{
    protected static ?string $model = Mission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Mission'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Name')),
                        Forms\Components\Textarea::make('description')
                            // max length type text
                            ->placeholder(__('Description')),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->maxSize(1024)
                            ->placeholder(__('Image')),
                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                            ->url()
                            ->placeholder(__('Url')),
                        Forms\Components\TextInput::make('reward')
                            ->mask(RawJs::make('$money($input)'))
                            ->default(0)
                            ->numeric()
                            ->placeholder(__('Reward')),
                        Forms\Components\TextInput::make('exp')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Exp')),
                    ])
                    ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exp')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMissions::route('/'),
            'create' => Pages\CreateMission::route('/create'),
            'edit' => Pages\EditMission::route('/{record}/edit'),
        ];
    }
}
