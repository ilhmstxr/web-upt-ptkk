<?php

namespace App\Filament\Clusters\Pelatihan\Resources;

use App\Filament\Clusters\Pelatihan;
use App\Filament\Clusters\Pelatihan\Resources\BidangResource\Pages;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BidangResource extends Resource
{
    protected static ?string $model = Bidang::class;

    protected static ?string $cluster = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Hide from sidebar navigation (akses via tab cluster saja)
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
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
                        ->defaultImageUrl(fn ($record) =>
                            'https://ui-avatars.com/api/?name=' .
                            urlencode($record->nama_bidang) .
                            '&size=300&background=random'
                        )
                        ->extraAttributes(['class' => 'rounded-lg object-cover w-full mb-3']),

                    Tables\Columns\TextColumn::make('nama_bidang')
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
            'index'  => Pages\ListBidangs::route('/'),
            'create' => Pages\CreateBidang::route('/create'),
            'edit'   => Pages\EditBidang::route('/{record}/edit'),
        ];
    }
}
