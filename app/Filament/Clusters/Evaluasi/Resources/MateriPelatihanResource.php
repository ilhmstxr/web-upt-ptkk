<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource\Pages;
use App\Models\MateriPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

// kalau kamu punya file cluster Evaluasi, aktifkan:
// use App\Filament\Clusters\Evaluasi;

class MateriPelatihanResource extends Resource
{
    protected static ?string $model = MateriPelatihan::class;

    // kalau pakai cluster, aktifkan:
    // protected static ?string $cluster = Evaluasi::class;

    protected static ?string $navigationIcon   = 'heroicon-o-book-open';
    protected static ?string $navigationGroup  = 'Evaluasi';
    protected static ?string $navigationLabel  = 'Materi Pelatihan';
    protected static ?string $modelLabel       = 'Materi';
    protected static ?string $pluralModelLabel = 'Materi Pelatihan';
    protected static ?int $navigationSort      = 20;

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
                        ->required(),

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
                        ->reactive(),

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
                        ->visible(fn ($get) => $get('tipe') === 'file')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('video_url')
                        ->label('Video URL')
                        ->url()
                        ->visible(fn ($get) => $get('tipe') === 'video')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('link_url')
                        ->label('Link URL')
                        ->url()
                        ->visible(fn ($get) => $get('tipe') === 'link')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('teks')
                        ->label('Isi Materi (Teks)')
                        ->visible(fn ($get) => $get('tipe') === 'teks')
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
            ->query(
                MateriPelatihan::query()
                    ->orderBy('pelatihan_id')
                    ->orderBy('urutan')
            )
            ->columns([
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->alignCenter()
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('tipe')
                    ->label('Tipe')
                    ->colors([
                        'info'    => 'video',
                        'success' => 'file',
                        'warning' => 'link',
                        'primary' => 'teks',
                    ])
                    ->icons([
                        'video' => 'heroicon-o-play-circle',
                        'file'  => 'heroicon-o-document',
                        'link'  => 'heroicon-o-link',
                        'teks'  => 'heroicon-o-pencil-square',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimasi_menit')
                    ->label('Estimasi')
                    ->suffix(' menit')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('tipe')
                    ->label('Tipe')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('pelatihan');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMateriPelatihans::route('/'),
            'create' => Pages\CreateMateriPelatihan::route('/create'),
            'view'   => Pages\ViewMateriPelatihan::route('/{record}'),
            'edit'   => Pages\EditMateriPelatihan::route('/{record}/edit'),
        ];
    }
}
