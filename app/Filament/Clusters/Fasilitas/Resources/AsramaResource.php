<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use App\Models\Asrama;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\Layout\Grid as ColumnGrid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use Illuminate\Database\Eloquent\Builder;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon   = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel  = 'Asrama';
    protected static ?string $modelLabel       = 'Asrama';
    protected static ?string $pluralModelLabel = 'Asrama';

    public static function getEloquentQuery(): Builder
    {
        // penting supaya badge counts tidak N+1
        return parent::getEloquentQuery()->withCount('kamars');
    }

    /**
     * Ambil default kamar dari config berdasarkan nama asrama.
     * Format repeater kamars: nomor_kamar, total_beds, is_active, available_beds
     */
    protected static function defaultKamarsForAsramaName(?string $asramaName): array
    {
        $asramaName = trim((string) $asramaName);
        if ($asramaName === '') return [];

        $config = config('kamar', []);
        if (!is_array($config) || !isset($config[$asramaName]) || !is_array($config[$asramaName])) {
            return [];
        }

        return collect($config[$asramaName])
            ->map(function ($room) {
                $no  = (int) ($room['no'] ?? 0);
                $bed = $room['bed'] ?? null;

                $isActive = is_numeric($bed) && (int) $bed > 0;
                $totalBeds = $isActive ? (int) $bed : 0;

                return [
                    'nomor_kamar'    => $no,
                    'total_beds'     => $totalBeds,
                    'available_beds' => $totalBeds,
                    'is_active'      => $isActive,
                ];
            })
            ->values()
            ->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Asrama')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Asrama')
                        ->required()
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                            /**
                             * Jika user membuat asrama baru dan namanya sama dengan key config,
                             * maka kamar otomatis terisi dari config (sekali).
                             *
                             * Tapi kalau user sudah mengisi repeater manual, jangan ditimpa.
                             */
                            $current = $get('kamars') ?? [];
                            if (!empty($current)) {
                                return;
                            }

                            $defaults = static::defaultKamarsForAsramaName($state);
                            if (!empty($defaults)) {
                                $set('kamars', $defaults);
                            }
                        }),

                ]),

            Forms\Components\Section::make('Kamar & Bed')
                ->description('Default otomatis dari config jika nama asrama sesuai. Tetap bisa diubah manual.')
                ->schema([

                    Forms\Components\Repeater::make('kamars')
                        ->relationship() // Asrama::kamars()
                        ->schema([

                            Forms\Components\TextInput::make('nomor_kamar')
                                ->label('Nomor Kamar')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('total_beds')
                                ->label('Total Bed')
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    // kalau user ubah total_beds, available_beds ikut disetarakan
                                    $beds = (int) $state;
                                    $set('available_beds', $beds);
                                    $set('is_active', $beds > 0);
                                }),

                            Forms\Components\TextInput::make('available_beds')
                                ->label('Bed Tersedia')
                                ->numeric()
                                ->minValue(0)
                                ->required(),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),

                        ])
                        ->columns(4)
                        ->addActionLabel('Tambah Kamar')
                        ->reorderable()
                        ->collapsible(),

                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Fasilitas Asrama')
            ->description('Asrama global (dari config), kamar bisa dipakai per pelatihan via kamar_pelatihan.')
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([

                    TextColumn::make('name')
                        ->label('')
                        ->size(TextColumn\TextColumnSize::Large)
                        ->weight('bold')
                        ->searchable()
                        ->sortable()
                        ->extraAttributes([
                            'class' => 'text-indigo-800 text-xl font-extrabold tracking-wide',
                        ]),

                    ColumnGrid::make(2)->schema([

                        BadgeColumn::make('kamars_count')
                            ->label('Kamar')
                            ->icon('heroicon-o-home')
                            ->color('primary')
                            ->alignCenter(),

                        BadgeColumn::make('total_beds')
                            ->label('Bed')
                            ->icon('heroicon-o-rectangle-stack')
                            ->state(fn (Asrama $record) => (int) $record->kamars()->sum('total_beds'))
                            ->color('success')
                            ->alignCenter(),

                    ]),
                ])
                ->space(3)
                ->extraAttributes([
                    'class' => '
                        p-6 rounded-3xl shadow-lg border-2 border-indigo-300
                        bg-gradient-to-br from-indigo-100 via-pink-100 to-yellow-100
                    ',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->button(),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->button(),
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
            RelationManagers\KamarRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'edit'   => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }
}
