<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?int $navigationSort = 5;
    // Sets the icon and places it on your sidebar
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Customers';

    // Disable the "Create" button since customers register themselves via OTP
    public static function canCreate(): bool
    {
        return false;
    }

    // Disable all Edit functionality so admins cannot alter customer data
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Details')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        
                        // Phone is disabled because it is their unique OTP login identifier
                        Forms\Components\TextInput::make('phone')
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\DatePicker::make('dob')
                            ->label('Date of Birth'),
                            
                        Forms\Components\Select::make('gender')
                            ->options([
                                'female' => 'Female',
                                'male' => 'Male',
                                'other' => 'Other',
                            ]),
                            
                        Forms\Components\Toggle::make('is_subscribed')
                            ->label('Subscribed to Newsletter'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Shows newest registered customers first
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable(['first_name', 'last_name', 'name'])
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click to copy'),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'female' => 'success',
                        'male' => 'info',
                        'other' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('is_subscribed')
                    ->label('Newsletter')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('is_verified')
                    ->label('Verification')
                    ->badge()
                    ->state(fn (Customer $record): string => $record->email_verified_at ? 'Verified' : 'Not Verified')
                    ->color(fn (string $state): string => $state === 'Verified' ? 'success' : 'danger'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered On')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-no-symbol')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Verification Status')
                    ->nullable()
                    ->trueLabel('Verified Customers')
                    ->falseLabel('Unverified Customers'),
                // Filter to quickly find users subscribed to your newsletter
                Tables\Filters\TernaryFilter::make('is_subscribed')
                    ->label('Newsletter Subscription')
                    ->boolean()
                    ->trueLabel('Subscribed Users')
                    ->falseLabel('Unsubscribed Users'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Removed EditAction::make() here
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Customer $record) => $record->is_active ? 'Suspend User' : 'Activate User')
                    ->color(fn (Customer $record) => $record->is_active ? 'danger' : 'success')
                    ->icon(fn (Customer $record) => $record->is_active ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Customer $record) => $record->is_active ? 'Suspend Customer?' : 'Activate Customer?')
                    ->modalDescription(fn (Customer $record) => $record->is_active 
                        ? 'Are you sure? This customer will no longer be able to log in or request OTPs.' 
                        : 'This customer will regain full access to log in to their account.')
                    ->action(function (Customer $record) {
                        $record->update(['is_active' => !$record->is_active]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            // We only need the index page. View will open in a clean slide-over modal!
            'index' => Pages\ListCustomers::route('/'),
        ];
    }
}