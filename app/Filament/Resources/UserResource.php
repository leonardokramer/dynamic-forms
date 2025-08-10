<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = null;
    
    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('users.labels.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('users.labels.email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label(__('users.labels.email_verified_at')),
                Forms\Components\TextInput::make('password')
                    ->label(__('users.labels.password'))
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('isAdmin')
                    ->label(__('users.labels.isAdmin')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('users.labels.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('users.labels.email'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('isAdmin')
                    ->label(__('users.labels.isAdmin'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('users.labels.email_verified_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('users.labels.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('users.labels.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getNavigationLabel(): string
    {
        return __('users.resources.user.navigation_label');
    }
    
    public static function getModelLabel(): string
    {
        return __('users.resources.user.model_label');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('users.resources.user.plural_model_label');
    }
}
