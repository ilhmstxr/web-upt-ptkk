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
    protected static ?string $modelLabel = 'Banner Slider';
    protected static ?string $navigationLabel = 'Banners';
    protected static ?string $pluralModelLabel = 'Daftar Banners';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Banner')
                    ->description('Atur gambar, teks, dan status banner.')
                    ->columns(2)
                    ->schema([

                        /**
                         * Primary file upload: simpan ke kolom 'gambar' (lebih eksplisit).
                         * Kalau projectmu masih memakai 'image', db tetap bisa diisi manual/legacy.
                         */
                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar Banner (unggah)')
                            ->image()
                            ->directory('banners')
                            ->disk('public')
                            ->maxSize(4096) // KB, ubah sesuai kebutuhan
                            ->imageCropAspectRatio('16:9')
                            ->columnSpanFull()
                            ->helperText('Unggah file gambar (jpg, png, webp). Jika migrasi dari kolom "image", bisa isi juga URL di field Image (opsional).'),

                        // Optional: kolom legacy/URL (jika ada data lama yang menyimpan full URL atau path di `image`)
                        Forms\Components\TextInput::make('image')
                            ->label('Image (URL / path legacy)')
                            ->placeholder('Contoh: banners/xxx.jpg atau https://... (opsional)')
                            ->columnSpanFull()
                            ->helperText('Isi hanya jika Anda menyimpan path/url gambar lama di kolom `image`. Jika diisi, akan digunakan jika kolom `gambar` kosong.'),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul/Teks Singkat')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->maxLength(65535)
                            ->rows(3)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->required()
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan banner tanpa menghapusnya.'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Banner Utama/Featured')
                            ->default(false)
                            ->helperText('Tandai sebagai banner utama atau yang diprioritaskan.'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Angka lebih kecil tampil lebih dulu.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /**
                 * ImageColumn: ambil prioritas dari 'gambar' -> 'image' -> null.
                 * Gunakan getStateUsing agar selalu menampilkan gambar meski kolom berbeda.
                 */
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->size(80)
                    ->getStateUsing(function ($record) {
                        // $record bisa berupa model atau array
                        $r = is_array($record) ? (object)$record : $record;

                        // prioritas: gambar (disk public path stored by FileUpload) -> image (legacy) -> null
                        $path = $r->gambar ?? $r->image ?? null;

                        // jika kosong, return null (Filament akan menampilkan placeholder default)
                        if (!$path) return null;

                        // Jika path sudah full URL, kembalikan apa adanya
                        if (preg_match('/^https?:\\/\\//i', $path)) {
                            return $path;
                        }

                        // Jika path terlihat seperti stored path di disk public (banners/...), ubah menjadi url storage
                        // Kita tidak bisa memanggil Storage di resource (berisiko), tapi Filament akan resolve /storage/...
                        // Pastikan kamu sudah menjalankan php artisan storage:link
                        // Normalisasi: jangan ganda 'storage/' jika pengguna sudah menyimpan 'storage/...'
                        $normalized = preg_replace('#^public\/+#i', '', $path);
                        $normalized = preg_replace('#^storage\/+#i', '', $normalized);

                        return '/storage/' . ltrim($normalized, '/');
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Featured')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Utama' : 'Reguler')
                    ->color(fn ($state): string => $state ? 'primary' : 'gray')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua'),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Banner Utama')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
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
