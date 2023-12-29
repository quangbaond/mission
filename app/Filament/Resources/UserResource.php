<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Account Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Name')),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignorable: fn ($record) => $record)
                            ->placeholder(__('Email')),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->placeholder(__('Password')),

                        Forms\Components\TextInput::make('balance')
                            ->mask(RawJs::make('$money($input)'))
                            ->default(0)
                            ->numeric()
                            ->placeholder(__('Balance')),
                        Forms\Components\TextInput::make('balance_withdraw')
                            ->mask(RawJs::make('$money($input)'))
                            ->default(0)
                            ->numeric()
                            ->placeholder(__('Balance Withdraw')),
                        Forms\Components\TextInput::make('balance_deposit')
                            ->mask(RawJs::make('$money($input)'))
                            ->default(0)
                            ->numeric()
                            ->placeholder(__('Balance Deposit')),
                        Forms\Components\Checkbox::make('is_admin')
                            ->label(__('Is Admin')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_verified_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_admin')
                    ->label(__('Is Admin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
                Tables\Columns\TextColumn::make('balance_pending')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
                Tables\Columns\TextColumn::make('balance_withdraw')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
                Tables\Columns\TextColumn::make('balance_deposit')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
            ])
            ->filters([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
