<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
// ✅ Sudah benar: Import Pages untuk definisi rute
use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource\Pages; 
// ✅ PERBAIKAN: Import Model yang benar (App\Models\Berita)
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
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 1, 'md' => 1, 'lg' => 1, 'xl' => 1]) // Dibuat responsif
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Berita')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => ($operation === 'create' || $operation === 'edit') ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Berita::class, 'slug', ignoreRecord: true),
                                
                                Forms\Components\RichEditor::make('content')
                                    ->label('Konten Berita')
                                    ->required()
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public') // Opsional: Konfigurasi upload gambar di editor
                                    ->fileAttachmentsDirectory('berita_content'), // Opsional: Konfigurasi folder
                            ]),
                        
                        Forms\Components\Group::make()
                            ->columnSpan(['sm' => 1, 'md' => 1, 'lg' => 1, 'xl' => 1]) // Dibuat responsif
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->directory('berita')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageEditor()
                                    ->columnSpanFull()
                                    ->required(), // Tambahkan required untuk memastikan gambar utama ada

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publikasikan Segera')
                                    ->default(false)
                                    ->inline(false)
                                    ->helperText('Aktifkan untuk menampilkan berita di website.'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal & Waktu Publikasi')
                                    ->default(now())
                                    ->required(fn (Forms\Get $get): bool => $get('is_published')) // Required jika is_published true
                                    ->visible(fn (Forms\Get $get): bool => $get('is_published') || $get('published_at')), // Tampilkan jika published atau sudah pernah diisi
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
                    ->square(), // Tampilkan sebagai kotak, lebih rapi di tabel
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita')
                    ->description(fn (Berita $record): string => Str::limit(strip_tags($record->content ?? ''), 70) . '...') // Batasan deskripsi lebih panjang
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Tambahkan wrap
                
                Tables\Columns\IconColumn::make('is_published') // Gunakan IconColumn untuk Toggle
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->color('primary')
                    ->since() // Tampilkan 'X hari yang lalu'
                    ->tooltip(fn (Berita $record): string => $record->published_at ? $record->published_at->format('d M Y H:i') : 'Belum dipublikasi'),
                    
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
                    ->indicator('Status Publikasi')
                    ->options([
                        true => 'Terpublikasi',
                        false => 'Draft',
                    ])
                    ->placeholder('Semua Berita'),
                    
                Tables\Filters\Filter::make('published_recently')
                    ->label('Baru Dipublikasi (7 hari)')
                    ->query(fn (Builder $query): Builder => $query->where('published_at', '>=', now()->subDays(7)))
                    ->toggle()
            ])
            ->actions([
                // ✅ Tambahkan ViewAction untuk melihat detail
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
            // ✅ Sudah benar
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
            'view' => Pages\ViewBerita::route('/{record}'), // Tambahkan View Page
        ];
    }
}