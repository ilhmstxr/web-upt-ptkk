<?php

namespace App\Filament\Resources\TesPertanyaanResource\Pages;

use App\Filament\Resources\TesPertanyaanResource;
use Filament\Actions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;

class EditTesPertanyaan extends EditRecord
{
    protected static string $resource = TesPertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Detail Pertanyaan')
                            ->description('Ubah informasi utama pertanyaan.')
                            ->schema([
                                Select::make('tes_id')
                                    ->relationship('tes', 'judul')
                                    ->label('Tes')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('nomor')
                                    ->label('Nomor Pertanyaan')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                                Select::make('tipe_jawaban')
                                    ->label('Tipe Jawaban')
                                    ->options([
                                        'pilihan_ganda' => 'Pilihan Ganda',
                                        'skala_likert' => 'Skala Likert',
                                        'teks_bebas' => 'Teks Bebas',
                                    ])
                                    ->required()
                                    ->live(),
                                Textarea::make('teks_pertanyaan')
                                    ->label('Teks Pertanyaan')
                                    ->rows(4)
                                    ->required(),
                                FileUpload::make('gambar')
                                    ->label('Gambar (Opsional)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pertanyaan')
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('150'),
                            ])
                            ->columns(2),
                        Section::make('Opsi Jawaban')
                            ->description('Kelola pilihan jawaban untuk pertanyaan ini.')
                            ->visible(fn(Get $get): bool => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert']))
                            ->schema([
                                Repeater::make('opsiJawabans')
                                    ->relationship('opsiJawabans')
                                    ->label('Daftar Opsi')
                                    ->schema([
                                        Textarea::make('teks_opsi')
                                            ->label('Teks Opsi')
                                            ->rows(2)
                                            ->required()
                                            ->columnSpan(2),
                                        FileUpload::make('gambar')
                                            ->label('Gambar Opsi (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('pertanyaan/opsi')
                                            ->maxSize(2048)
                                            ->imagePreviewHeight('150')
                                            ->columnSpan(2),
                                        Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar')
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, ?bool $state, Component $component): void {
                                                if (! $state) {
                                                    return;
                                                }

                                                $currentPath = $component->getStatePath();
                                                $items = $get('opsiJawabans') ?? [];

                                                foreach (array_keys($items) as $index) {
                                                    $path = "opsiJawabans." . $index . '.apakah_benar';
                                                    if ($path !== $currentPath) {
                                                        $set($path, false);
                                                    }
                                                }
                                            })
                                            ->columnSpan(1),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(4)
                                    ->reorderable()
                                    ->collapsed(),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }
}
