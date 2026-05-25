<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserQueryResource\Pages;
use App\Models\UserQuery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class UserQueryResource extends Resource
{
    protected static ?int $navigationSort = 5; // ADD THIS LINE
    protected static ?string $model = UserQuery::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'User Queries';
    
    // REMOVED: protected static ?string $navigationGroup = 'Communications';

    // Disable the "Create" button since admins don't write user queries
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Query Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->disabled(),
                        Forms\Components\TextInput::make('reason')
                            ->disabled(),
                        Forms\Components\Textarea::make('message')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(5),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Show newest queries at the top by default
            ->defaultSort('created_at', 'desc') 
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click to copy'),

                Tables\Columns\TextColumn::make('reason')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received On')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->actions([
                // The View button opens the form in read-only mode
                Tables\Actions\ViewAction::make(),
                
                // Custom Action: Mark as Read
                Tables\Actions\Action::make('mark_as_read')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    // Hide this button if the query is already read
                    ->hidden(fn (UserQuery $record) => $record->is_read) 
                    ->action(fn (UserQuery $record) => $record->update(['is_read' => true])),
                    
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // Custom Bulk Action: Mark multiple as read at once
                    Tables\Actions\BulkAction::make('mark_as_read')
                        ->label('Mark Selected as Read')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_read' => true]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            // Removed 'create' and 'edit' pages to keep it View-only
            'index' => Pages\ListUserQueries::route('/'),
        ];
    }
}