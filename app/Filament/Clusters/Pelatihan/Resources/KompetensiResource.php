<?php

namespace App\Filament\Clusters\Pelatihan\Resources;

use App\Filament\Clusters\Pelatihan;
<<<<<<< HEAD:app/Filament/Clusters/Pelatihan/Resources/BidangResource.php
use App\Filament\Clusters\Pelatihan\Resources\BidangResource\Pages;
use App\Models\Bidang;
=======
use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\Pages;
use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\RelationManagers;
use App\Models\Kompetensi;
>>>>>>> ilham-widget-ui-overhaul-custom:app/Filament/Clusters/Pelatihan/Resources/KompetensiResource.php
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KompetensiResource extends Resource
{
    protected static ?string $model = \App\Models\Kompetensi::class;

    protected static ?string $cluster = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Hide from sidebar navigation (akses via tab cluster saja)
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
<<<<<<< HEAD:app/Filament/Clusters/Pelatihan/Resources/BidangResource.php
                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar/Icon Bidang')
                    ->disk('public')                 // simpan di disk public
                    ->directory('bidang-images')     // folder: storage/app/public/bidang-images
                    ->visibility('public')           // bisa diakses via /storage/...
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                        '4:3',
                        '16:9',
                    ])
                    ->maxSize(2048) // 2MB
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('nama_bidang')
                    ->label('Nama Bidang')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('kode')
                    ->label('Kode Bidang')
                    ->maxLength(255),

                Forms\Components\Select::make('kelas_keterampilan')
                    ->label('Kelompok Bidang')
                    ->options([
                        1 => 'Kelas Keterampilan & Teknik',
                        0 => 'Milenial Job Center',
                    ])
                    ->required()
                    ->native(false)
                    ->default(1),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(65535)
                    ->rows(4)
                    ->columnSpanFull(),
=======
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
                                            
                                        Forms\Components\TextInput::make('kelas_keterampilan')
                                            ->label('Kelas Keterampilan')
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Textarea::make('deskripsi')
                                            ->label('Deskripsi')
                                            ->maxLength(65535)
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])->columnSpan(['lg' => 2]),

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
                    ])->columnSpan(['lg' => 1]),
>>>>>>> ilham-widget-ui-overhaul-custom:app/Filament/Clusters/Pelatihan/Resources/KompetensiResource.php
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
                    // Gambar di atas
                    Tables\Columns\ImageColumn::make('gambar')
                        ->disk('public') // ambil dari disk public
                        ->height(150)
<<<<<<< HEAD:app/Filament/Clusters/Pelatihan/Resources/BidangResource.php
                        ->defaultImageUrl(fn ($record) =>
                            'https://ui-avatars.com/api/?name=' .
                            urlencode($record->nama_bidang) .
                            '&size=300&background=random'
                        )
                        ->extraAttributes(['class' => 'rounded-lg object-cover w-full mb-3']),

                    Tables\Columns\TextColumn::make('nama_bidang')
=======
                        ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_kompetensi) . '&size=300&background=random')
                        ->extraAttributes(['class' => 'rounded-lg object-cover w-full mb-3'])
                        ->disk('public'),
                    
                    Tables\Columns\TextColumn::make('nama_kompetensi')
>>>>>>> ilham-widget-ui-overhaul-custom:app/Filament/Clusters/Pelatihan/Resources/KompetensiResource.php
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
<<<<<<< HEAD:app/Filament/Clusters/Pelatihan/Resources/BidangResource.php
            'index'  => Pages\ListBidangs::route('/'),
            'create' => Pages\CreateBidang::route('/create'),
            'edit'   => Pages\EditBidang::route('/{record}/edit'),
=======
            'index' => Pages\ListKompetensi::route('/'),
            'create' => Pages\CreateKompetensi::route('/create'),
            'edit' => Pages\EditKompetensi::route('/{record}/edit'),
>>>>>>> ilham-widget-ui-overhaul-custom:app/Filament/Clusters/Pelatihan/Resources/KompetensiResource.php
        ];
    }
}
