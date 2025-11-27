<?php

namespace App\Filament\Clusters\Alumni\Resources;

use App\Filament\Clusters\Alumni;
use App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Pages;
use App\Filament\Clusters\Alumni\Resources\TracerStudyResource\RelationManagers;
use App\Models\TracerStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets\TracerStudyStatsWidget;
use App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets\TracerStudyChart;
use App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets\TracerStudySalaryChart;

class TracerStudyResource extends Resource
{
    protected static ?string $model = TracerStudy::class;

    protected static ?string $cluster = Alumni::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->label('Alumni'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Bekerja' => 'Bekerja',
                        'Wirausaha' => 'Wirausaha',
                        'Mencari Kerja' => 'Mencari Kerja',
                        'Melanjutkan Studi' => 'Melanjutkan Studi',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('company')
                    ->label('Nama Perusahaan / Usaha')
                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['Bekerja', 'Wirausaha']))
                    ->maxLength(255),
                Forms\Components\TextInput::make('salary')
                    ->label('Pendapatan Bulanan (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['Bekerja', 'Wirausaha'])),
                Forms\Components\TextInput::make('waiting_period')
                    ->label('Masa Tunggu (Bulan)')
                    ->numeric()
                    ->helperText('Berapa bulan setelah lulus hingga mendapatkan pekerjaan?')
                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['Bekerja', 'Wirausaha'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Alumni')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bekerja' => 'success',
                        'Wirausaha' => 'info',
                        'Mencari Kerja' => 'warning',
                        'Melanjutkan Studi' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->money('IDR')
                    ->label('Gaji')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waiting_period')
                    ->label('Masa Tunggu (Bln)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Bekerja' => 'Bekerja',
                        'Wirausaha' => 'Wirausaha',
                        'Mencari Kerja' => 'Mencari Kerja',
                        'Melanjutkan Studi' => 'Melanjutkan Studi',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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


    public static function getWidgets(): array
    {
        return [
            TracerStudyStatsWidget::class,
            TracerStudyChart::class,
            TracerStudySalaryChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTracerStudies::route('/'),
            'create' => Pages\CreateTracerStudy::route('/create'),
            'edit' => Pages\EditTracerStudy::route('/{record}/edit'),
        ];
    }
}
