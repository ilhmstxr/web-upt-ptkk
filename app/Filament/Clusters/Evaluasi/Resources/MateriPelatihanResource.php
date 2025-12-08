<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource\Pages;
use App\Models\MateriPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MateriPelatihanResource extends Resource
{
    protected static ?string $model = MateriPelatihan::class;

    // ✅ konsisten kayak TesResource
    protected static ?string $cluster = Evaluasi::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Materi Pelatihan';
    protected static ?string $modelLabel      = 'Materi';
    protected static ?int $navigationSort     = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Materi')
                ->schema([
                    Forms\Components\Select::make('pelatihan_id')
                        ->label('Pelatihan')
                        ->relationship('pelatihan', 'nama_pelatihan')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(request()->query('pelatihan_id'))
                        ->disabled(fn (?string $operation)
                            => $operation === 'edit' || request()->has('pelatihan_id')
                        ),

                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Materi')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipe')
                        ->label('Tipe Materi')
                        ->required()
                        ->options([
                            'video' => 'Video',
                            'file'  => 'File',
                            'link'  => 'Link',
                            'teks'  => 'Teks',
                        ])
                        ->default('file')
                        ->live(), // biar visible() langsung update

                    Forms\Components\TextInput::make('urutan')
                        ->label('Urutan')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Forms\Components\TextInput::make('estimasi_menit')
                        ->label('Estimasi (menit)')
                        ->numeric()
                        ->nullable(),

                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi Singkat')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Konten Materi')
                ->schema([
                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload File')
                        ->disk('public')
                        ->directory('materi')
                        ->openable()
                        ->downloadable()
                        ->visible(fn (Forms\Get $get) => $get('tipe') === 'file')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('video_url')
                        ->label('Video URL')
                        ->url()
                        ->visible(fn (Forms\Get $get) => $get('tipe') === 'video')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('link_url')
                        ->label('Link URL')
                        ->url()
                        ->visible(fn (Forms\Get $get) => $get('tipe') === 'link')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('teks')
                        ->label('Isi Materi (Teks)')
                        ->visible(fn (Forms\Get $get) => $get('tipe') === 'teks')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Publish?')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'video' => 'info',
                        'file'  => 'success',
                        'link'  => 'warning',
                        'teks'  => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('estimasi_menit')
                    ->label('Estimasi')
                    ->numeric()
                    ->suffix(' menit')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'video' => 'Video',
                        'file'  => 'File',
                        'link'  => 'Link',
                        'teks'  => 'Teks',
                    ]),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Publish?'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('urutan', 'asc');
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
            'index'  => Pages\ListMateriPelatihans::route('/'),
            'create' => Pages\CreateMateriPelatihan::route('/create'),
            'edit'   => Pages\EditMateriPelatihan::route('/{record}/edit'),
        ];
    }
}
