<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\MateriPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MateriPelatihanTable extends BaseWidget
{
    public $record; // record Pelatihan

    protected static ?string $heading = 'Daftar Materi Pelatihan';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MateriPelatihan::query()
                    ->where('pelatihan_id', $this->record->id)
                    ->orderBy('urutan')
            )
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),

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
                    ]),

                Tables\Columns\TextColumn::make('estimasi_menit')
                    ->label('Estimasi')
                    ->suffix(' menit')
                    ->toggleable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('excerpt')
                    ->label('Ringkasan')
                    ->limit(60)
                    ->wrap()
                    ->color('secondary'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Materi')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data) {
                        // inject pelatihan_id biar auto nempel ke pelatihan ini
                        $data['pelatihan_id'] = $this->record->id;
                        return $data;
                    })
                    ->form([
                        Forms\Components\Grid::make(2)->schema([

                            Forms\Components\TextInput::make('judul')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('tipe')
                                ->required()
                                ->options([
                                    'video' => 'Video',
                                    'file'  => 'File',
                                    'link'  => 'Link',
                                    'teks'  => 'Teks',
                                ])
                                ->reactive(),

                            Forms\Components\TextInput::make('urutan')
                                ->numeric()
                                ->default(1)
                                ->required(),

                            Forms\Components\TextInput::make('estimasi_menit')
                                ->numeric()
                                ->label('Estimasi (menit)')
                                ->nullable(),

                            Forms\Components\Textarea::make('deskripsi')
                                ->rows(3)
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('file_path')
                                ->label('File Materi')
                                ->disk('public')
                                ->directory('materi')
                                ->visible(fn ($get) => $get('tipe') === 'file')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('video_url')
                                ->label('Video URL')
                                ->visible(fn ($get) => $get('tipe') === 'video')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('link_url')
                                ->label('Link URL')
                                ->visible(fn ($get) => $get('tipe') === 'link')
                                ->columnSpanFull(),

                            Forms\Components\RichEditor::make('teks')
                                ->label('Isi Teks Materi')
                                ->visible(fn ($get) => $get('tipe') === 'teks')
                                ->columnSpanFull(),

                            Forms\Components\Toggle::make('is_published')
                                ->default(true)
                                ->label('Publish?'),

                        ]),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ]);
    }
}
