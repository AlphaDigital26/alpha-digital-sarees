<?php

namespace App\Livewire;

use App\Models\Fabric;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class FabricTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    // 1. REUSABLE FORM SCHEMA: Now 'Create' and 'Edit' will always be identical!
    protected function getFabricFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
                
            Forms\Components\FileUpload::make('image')
                ->label('Fabric Image (Optional)')
                ->image()
                ->directory('fabrics')
                ->helperText('Upload an image if you want to feature this on the homepage.'),
                
            Forms\Components\Toggle::make('is_featured')
                ->label('Feature on Homepage')
                ->helperText('Turn this on to show this fabric in the homepage grid (max 4).')
                // 2. VALIDATION RULE: Enforces the maximum limit of 4 featured fabrics
                ->rule(static function (?Fabric $record) {
                    return static function (string $attribute, $value, \Closure $fail) use ($record) {
                        // Only run the check if the admin is trying to turn the toggle ON
                        if ($value === true) {
                            
                            // Count how many fabrics are currently featured...
                            $count = Fabric::where('is_featured', true)
                                // ...but ignore the one we are currently editing (so it doesn't count against itself)
                                ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                                ->count();
                            
                            // If there are already 4 or more, block the save and show this message
                            if ($count >= 4) {
                                $fail('You already have 4 fabrics featured on the homepage. Please edit another fabric and uncheck it first.');
                            }
                        }
                    };
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Fabric::query())
            ->heading('Fabrics')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Fabric&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('On Homepage')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->model(Fabric::class)
                    ->form($this->getFabricFormSchema()), // Uses the shared schema
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form($this->getFabricFormSchema()), // Uses the exact same shared schema
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}