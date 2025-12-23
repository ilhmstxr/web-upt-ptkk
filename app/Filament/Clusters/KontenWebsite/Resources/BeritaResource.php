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
use Filament\Forms\Components\FileUpload;
use Carbon\Carbon;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;
    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
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
                        /* ================= KIRI ================= */
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                               Forms\Components\TextInput::make('title')
    ->label('Judul Berita')
    ->required()
    ->maxLength(255)
    ->reactive()
    ->afterStateUpdated(function (?string $state, callable $set) {
        $set('slug', Str::slug($state ?? ''));
    }),

Forms\Components\TextInput::make('slug')
    ->required()
    ->maxLength(255)
    ->unique(Berita::class, 'slug', ignoreRecord: true)
    ->hidden()                 // ✅ tidak tampil
    ->dehydrated(true),        // ✅ tetap ikut submit ke DB

                                Forms\Components\RichEditor::make('content')
                                    ->label('Konten Berita')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('konten-website/berita/content')
                                    ->columnSpanFull(),
                            ]),

                        /* ================= KANAN ================= */
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 2, 'lg' => 1])
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->disk('public')
                                    ->directory('konten-website/berita/thumbnail')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageEditor()
                                    ->maxSize(4096)
                                    ->helperText('Unggah gambar utama (maks 4MB) dengan rasio 16:9.'),

                              Forms\Components\Toggle::make('is_published')
    ->label('Publikasikan Segera')
    ->default(false)
    ->reactive()
    ->afterStateUpdated(function (bool $state, callable $set, callable $get) {
        if ($state && blank($get('published_at'))) {
            $set('published_at', now());
        }

        if (! $state) {
            $set('published_at', null);
        }
    }),

Forms\Components\DateTimePicker::make('published_at')
    ->label('Tanggal & Waktu Publikasi')
    ->nullable()
    ->withoutSeconds()
    ->displayFormat('d F Y H:i')
    ->visible(fn (Forms\Get $get): bool => (bool) $get('is_published'))
    ->required(fn (Forms\Get $get): bool => (bool) $get('is_published'))
    ->maxDate(Carbon::now('Asia/Jakarta')->endOfDay()) // ✅ tanggal 17 pasti bisa diklik
    ->rule('before_or_equal:now'),



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
                    ->disk('public')
                    ->size(80)
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita')
                    ->description(fn (Berita $record) =>
                        Str::limit(strip_tags($record->content ?? ''), 70)
                    )
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
                    ->sortable(),

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
                    ->query(fn (Builder $query) =>
                        $query->where('published_at', '>=', now()->subDays(7))
                    ),
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
