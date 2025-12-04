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
                Forms\Components\Section::make('Informasi Alumni')
                    ->schema([
                        Forms\Components\Grid::make(2)
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
                            ]),
                    ]),
                Forms\Components\Section::make('Detail Pekerjaan / Usaha')
                    ->description('Isi jika status Bekerja atau Wirausaha')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('company')
                                    ->label('Nama Perusahaan / Usaha')
                                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['Bekerja', 'Wirausaha']))
                                    ->maxLength(255)
                                    ->columnSpanFull(),
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
                            ]),
                    ])
                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['Bekerja', 'Wirausaha'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Alumni')
                    ->description(fn ($record) => $record->user->email ?? '-')
                    ->searchable()
                    ->sortable()
                    ->icon(fn ($record) => 'heroicon-o-user-circle'), // Placeholder for avatar
                
                // Tables\Columns\TextColumn::make('batch') // Assuming batch exists or relation
                //     ->label('Batch Pelatihan')
                //     ->default('Web Dev Batch 3') // Placeholder if not in DB yet
                //     ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Saat Ini')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bekerja' => 'success',
                        'Wirausaha' => 'info', // Purple in HTML, mapping to info or custom
                        'Mencari Kerja' => 'warning',
                        'Melanjutkan Studi' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('job_info')
                    ->label('Posisi / Perusahaan')
                    ->getStateUsing(fn ($record) => $record->company ? $record->company : '-')
                    ->description(fn ($record) => $record->position ?? '-') // Assuming position column exists
                    ->visible(fn ($record) => in_array($record->status, ['Bekerja', 'Wirausaha'])),
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
