<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $cluster = KontenWebsite::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $modelLabel = 'Banner';
    protected static ?string $navigationLabel = 'Banner';
    protected static ?string $pluralModelLabel = 'Banner';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Banner ini akan ditampilkan pada halaman Beranda')
                    ->description('Usahakan ukuran gambar kurang dari 4 MB untuk performa website')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Banner (unggah)')
                            ->image()
                            ->disk('public') // ⬅ FILE DISIMPAN DI storage/app/public
                            ->directory('konten-website/banners') // ⬅ FOLDER KHUSUS BANNER
                            ->preserveFilenames() // opsional, biar nama file tidak diacak
                            ->maxSize(4096) // KB
                            ->imageCropAspectRatio('16:9')
                            ->columnSpanFull()
                            ->helperText('Unggah file gambar (jpg, png, webp).')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Tampilkan Banner')
                            ->required()
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan banner tanpa menghapusnya.'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan Pertama Kali')
                            ->default(false)
                            ->helperText('Banner ini akan ditampilkan pertama kali di beranda.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public'), // ⬅ ambil dari disk public (storage/app/public)

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua'),
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

    /**
     * Pastikan hanya satu banner yang featured.
     * Dipanggil waktu CREATE.
     */
    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['is_featured'] ?? false) {
            Banner::query()->update(['is_featured' => false]);
        }

        return $data;
    }

    /**
     * Biar pas EDIT juga tetap cuma satu yang featured.
     */
    protected static function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['is_featured'] ?? false) {
            Banner::query()->update(['is_featured' => false]);
        }

        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
