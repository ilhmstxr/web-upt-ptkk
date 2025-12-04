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
                        // KIRI: Judul, slug, konten (mengisi kolom utama)
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Berita')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!empty($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Berita::class, 'slug', ignoreRecord: true)
                                    ->helperText('Biarkan terisi otomatis dari judul atau ubah sesuai kebutuhan.'),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Konten Berita')
                                    ->required()
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('berita_content'),
                            ]),

                        // KANAN: Gambar, publish toggle & tanggal
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->directory('berita')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageCropAspectRatio('16:9')
                                    ->maxSize(4096) // KB (4MB)
                                    ->columnSpanFull()
                                    ->helperText('Unggah gambar utama untuk tampilan featured.'),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publikasikan Segera')
                                    ->default(false)
                                    ->helperText('Aktifkan untuk menampilkan berita di website.'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal & Waktu Publikasi')
                                    ->default(now())
                                    ->visible(fn (Forms\Get $get): bool => (bool) $get('is_published'))
                                    ->required(fn (Forms\Get $get): bool => (bool) $get('is_published')),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn: aman dengan path relatif atau full URL
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->size(80)
                    ->square()
                    ->getStateUsing(function ($record) {
                        // $record bisa model atau array
                        $r = is_array($record) ? (object)$record : $record;
                        $path = $r->image ?? null;
                        if (! $path) {
                            return null;
                        }
                        // jika full URL langsung kembalikan
                        if (preg_match('/^https?:\\/\\//i', $path)) {
                            return $path;
                        }
                        // normalisasi: hapus awalan storage/ atau public/
                        $normalized = preg_replace('#^public\/+#i', '', $path);
                        $normalized = preg_replace('#^storage\/+#i', '', $normalized);
                        return '/storage/' . ltrim($normalized, '/');
                    }),

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
                    ->color('primary')
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
            'view'   => Pages\ViewBerita::route('/{record}'),
        ];
    }
}
