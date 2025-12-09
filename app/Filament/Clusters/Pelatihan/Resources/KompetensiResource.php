<?php

namespace App\Filament\Clusters\Pelatihan\Resources;

use App\Filament\Clusters\Pelatihan;
use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\Pages;
use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\RelationManagers;
use App\Models\Kompetensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KompetensiResource extends Resource
{
    protected static ?string $model = Kompetensi::class;

    protected static ?string $cluster = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Hide from sidebar navigation (akses via tab cluster saja)
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                // ==================== KOLOM KIRI ====================
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Kompetensi')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_kompetensi')
                                            ->label('Nama Kompetensi')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('kode')
                                            ->label('Kode Kompetensi')
                                            ->maxLength(255),

                                        Forms\Components\Select::make('kelas_keterampilan')
                                            ->label('Kelompok')
                                            ->options([
                                                1 => 'Kelas Keterampilan & Teknik',
                                                0 => 'Milenial Job Center',
                                            ])
                                            ->required()
                                            ->native(false)
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('deskripsi')
                                            ->label('Deskripsi')
                                            ->maxLength(65535)
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                // ==================== KOLOM KANAN ====================
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Media')
                            ->schema([
                                Forms\Components\FileUpload::make('gambar')
                                    ->label('Gambar/Icon Kompetensi')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                        '4:3',
                                        '16:9',
                                    ])
                                    ->maxSize(2048) // 2MB
                                    ->directory('kompetensi-images')
                                    ->visibility('public')
                                    ->disk('public'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('gambar')
                        ->disk('public')
                        ->height(150)
                        ->defaultImageUrl(fn ($record) =>
                            'https://ui-avatars.com/api/?name=' . urlencode($record->nama_kompetensi) . '&size=300&background=random'
                        )
                        ->extraAttributes(['class' => 'rounded-lg object-cover w-full mb-3']),

                    Tables\Columns\TextColumn::make('nama_kompetensi')
                        ->weight('bold')
                        ->size('lg')
                        ->icon('heroicon-o-academic-cap')
                        ->color('primary')
                        ->extraAttributes(['class' => 'mb-2']),

                    Tables\Columns\TextColumn::make('kelas_keterampilan')
                        ->label('Kelompok')
                        ->formatStateUsing(fn ($state) => $state
                            ? 'Kelas Keterampilan & Teknik'
                            : 'Milenial Job Center'
                        )
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'warning'),

                    Tables\Columns\TextColumn::make('deskripsi')
                        ->limit(100)
                        ->color('gray')
                        ->size('sm'),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->date()
                            ->icon('heroicon-o-calendar')
                            ->color('gray')
                            ->size('xs'),
                    ])->extraAttributes(['class' => 'mt-4 pt-4 border-t border-gray-200']),
                ])->space(3),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKompetensi::route('/'),
            'create' => Pages\CreateKompetensi::route('/create'),
            'edit'   => Pages\EditKompetensi::route('/{record}/edit'),
        ];
    }
}
