<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource\Pages;
use App\Models\Berita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload; // Pastikan ini diimpor

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Berita & Artikel';
    protected static ?string $navigationLabel = 'Berita';
    protected static ?string $pluralModelLabel = 'Daftar Berita';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Berita')
                    ->description('Informasi utama, konten, dan gambar berita.')
                    ->columns(2)
                    ->schema([
                        // KIRI: Judul, slug, konten
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Berita')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, callable $set) {
                                        // Update slug secara otomatis (hanya saat input blur/hilang fokus)
                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->maxLength(255)
                                    // Memastikan slug unik kecuali saat edit record yang sama
                                    ->unique(Berita::class, 'slug', ignoreRecord: true)
                                    ->helperText('Biarkan terisi otomatis dari judul atau ubah jika perlu.'),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Konten Berita')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('berita_content')
                                    ->columnSpanFull(),
                            ]),

                        // KANAN: Gambar, publish toggle & tanggal
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                                // Komponen FileUpload yang Eksplisit
                                FileUpload::make('image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->directory('berita')
                                    ->disk('public')          // <--- Sangat penting: Tentukan disk public
                                    ->visibility('public')    // <--- Sangat penting: Tentukan visibility public
                                    ->imageCropAspectRatio('16:9')
                                    ->imageEditor()
                                    ->maxSize(4096)
                                    ->helperText('Unggah gambar utama (maks 4MB) dengan rasio 16:9.'),
                                    
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publikasikan Segera')
                                    ->default(false)
                                    ->reactive() // Membuat field di bawahnya bisa bereaksi
                                    ->helperText('Aktifkan untuk menampilkan berita di website.'),

                                // DateTimePicker
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal & Waktu Publikasi')
                                    ->nullable()
                                    ->default(now()) // Default ke waktu sekarang
                                    ->withoutSeconds()
                                    ->displayFormat('d F Y H:i')
                                    ->placeholder('Pilih tanggal & jam publikasi (opsional)')
                                    // Hanya tampilkan jika 'is_published' aktif
                                    ->visible(fn (Forms\Get $get): bool => (bool) $get('is_published'))
                                    // Wajib diisi jika 'is_published' aktif
                                    ->required(fn (Forms\Get $get): bool => (bool) $get('is_published'))
                                    ->helperText('Waktu publikasi yang sebenarnya. Wajib diisi jika status diaktifkan.'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->size(80)
                    ->square(), // Menggunakan accessor bawaan Filament (perlu `php artisan storage:link`)

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita')
                    ->description(fn (Berita $record): string => Str::limit(strip_tags($record->content ?? ''), 70))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->tooltip(fn (Berita $record): ?string => $record->published_at ? $record->published_at->format('d M Y H:i') : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->placeholder('Semua'),

                Tables\Filters\Filter::make('published_recently')
                    ->label('Baru Dipublikasi (7 hari)')
                    ->query(fn (Builder $query): Builder => $query->where('published_at', '>=', now()->subDays(7)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit'   => Pages\EditBerita::route('/{record}/edit'),
            'view'   => Pages\ViewBerita::route('/{record}'), // Menambahkan halaman view
        ];
    }
}