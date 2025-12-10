<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\MateriResource\Pages;
use App\Models\Materi;
use Filament\Forms;
use Filament\Forms\Form as FormsForm;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MateriResource extends Resource
{
    protected static ?string $model = Materi::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $cluster = Evaluasi::class;

    public static function form(FormsForm $form): FormsForm
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')
                ->label('Judul')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(Materi::class, 'slug', ignoreRecord: true),

            Forms\Components\TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),

            Forms\Components\Select::make('kategori')
                ->label('Kategori')
                ->options(fn() => Materi::query()->distinct()->pluck('kategori','kategori')->filter()->toArray())
                ->searchable()
                ->nullable(),

            Forms\Components\TextInput::make('durasi')
                ->label('Durasi (menit)')
                ->numeric()
                ->nullable(),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(3)
                ->nullable(),

            Forms\Components\RichEditor::make('konten')
                ->label('Konten')
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('materi_content')
                ->nullable(),

            Forms\Components\FileUpload::make('file_pendukung')
                ->label('File Pendukung (PDF/ZIP)')
                ->directory('materi_files')
                ->disk('public')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')->label('No')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('judul')->label('Judul')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('durasi')->label('Durasi (m)')->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')->label('Kategori')->options(fn() => Materi::query()->pluck('kategori','kategori')->filter()->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit'   => Pages\EditMateri::route('/{record}/edit'),
            'view'   => Pages\ViewMateri::route('/{record}'),
        ];
    }
}
