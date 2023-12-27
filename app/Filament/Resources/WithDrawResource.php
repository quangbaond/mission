<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithDrawResource\Pages;
use App\Filament\Resources\WithDrawResource\RelationManagers;
use App\Models\WithDraw;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;

class WithDrawResource extends Resource
{
    protected static ?string $model = WithDraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->autofocus()
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('card_name')
                    ->options([
                        'Viettel', 'Vinaphone', 'Mobifone', 'Vietnamobile', 'Garenar', 'Zing', 'Vcoin', 'Gate'
                    ]),
                Forms\Components\Select::make('card_value')
                    ->options([
                        '10000', '20000', '30000', '50000', '100000', '200000', '300000', '500000', '1000000', '2000000', '3000000', '5000000', '10000000'
                    ]),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('bank_number'),

                Forms\Components\TextInput::make('bank_owner'),
                Forms\Components\Select::make('method')
                    ->required()
                    ->options([
                        'bank' => 'ATM',
                        'momo' => 'Internet Banking',
                        'card' => 'Mobile Banking',
                    ]),
                Forms\Components\Select::make('bank_name')
                    ->options([
                        'Vietinbank', 'Vietcombank', 'BIDV', 'Techcombank', 'VPBank', 'ACB', 'MBBank', 'TPBank', 'HDBank', 'SHB', 'VIB', 'SeABank', 'BacABank', 'OceanBank', 'Eximbank', 'MSB', 'LienVietPostBank', 'NamABank', 'PGBank', 'VietCapitalBank', 'VietABank', 'BaoVietBank', 'KienLongBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB', 'BacABank', 'BaoVietBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB', 'BacABank', 'BaoVietBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB', 'BacABank', 'BaoVietBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB', 'BacABank', 'BaoVietBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB', 'BacABank', 'BaoVietBank', 'VietBank', 'Orient Commercial Bank', 'BacA Bank', 'PvcomBank', 'VRB', 'BVB', 'GPBank', 'NCB', 'SCB', 'BVB', 'OCB',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn(WithDraw $record) => match ($record->status) {
                        0 => 'warning',
                        1 => 'success',
                        2 => 'danger',
                    })
                    ->formatStateUsing(fn(WithDraw $record) => match ($record->status) {
                        0 => 'Pending',
                        1 => 'Accepted',
                        2 => 'Rejected',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions(actions: [


                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Accept')
                        ->action(function (WithDraw $record) {
                            $record->update(['status' => 1]);
                            $record->user->update([
                                'balance_pending' => $record->user->balance_pending - $record->amount,
                                'balance_withdraw' => $record->user->balance_withdraw + $record->amount,
                            ]);
                        })
                        ->icon('heroicon-s-check')
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('Reject')
                        ->action(function (WithDraw $record) {
                            $record->update(['status' => 2]);
                            $record->user->update([
                                'balance_pending' => $record->user->balance_pending - $record->amount,
                                'balance' => $record->user->balance + $record->amount,
                            ]);
                        })
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])


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
            'index' => Pages\ListWithDraws::route('/'),
            'create' => Pages\CreateWithDraw::route('/create'),
            'edit' => Pages\EditWithDraw::route('/{record}/edit'),
        ];
    }
}
