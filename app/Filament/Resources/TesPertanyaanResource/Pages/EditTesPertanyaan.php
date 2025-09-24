<?php

namespace App\Filament\Resources\TesPertanyaanResource\Pages;

use App\Filament\Resources\TesPertanyaanResource;
use Filament\Actions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload; // Pastikan FileUpload di-import
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Arr;
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

                                // Menggunakan FileUpload standar, sama seperti di TesPertanyaanResource
                                FileUpload::make('gambar')
                                    ->label('Gambar Pertanyaan')
                                    ->image()
                                    ->directory('pertanyaan')
                                    ->disk('public')
                                    ->nullable()
                                    ->maxSize(2048),
                            ])
                            ->columns(2),

                        Section::make('Opsi Jawaban')
                            ->description('Kelola pilihan jawaban untuk pertanyaan ini.')
                            ->visible(fn(Get $get): bool => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert']))
                            ->schema([
                                Hidden::make('opsi_benar_path')->dehydrated(false),

                                Repeater::make('opsiJawabans')
                                    ->relationship('opsiJawabans')
                                    ->label('Daftar Opsi')
                                    ->mutateRelationshipDataBeforeCreateUsing(fn(array $data) => Arr::only($data, ['teks_opsi', 'gambar', 'apakah_benar']))
                                    ->mutateRelationshipDataBeforeSaveUsing(fn(array $data) => Arr::only($data, ['teks_opsi', 'gambar', 'apakah_benar']))
                                    ->afterStateHydrated(function (Get $get, Set $set, ?array $state) {
                                        foreach (array_keys($state ?? []) as $key) {
                                            if ($get("opsiJawabans.$key.apakah_benar")) {
                                                $set('opsi_benar_path', "opsiJawabans.$key.apakah_benar");
                                                break;
                                            }
                                        }
                                    })
                                    ->schema([
                                        Textarea::make('teks_opsi')
                                            ->label('Teks Opsi')
                                            ->rows(2)
                                            ->required()
                                            ->columnSpan(3), // Dibuat full width untuk konsistensi

                                        // PERUBAHAN UTAMA DI SINI:
                                        // Mengganti ViewField kustom dengan FileUpload standar
                                        FileUpload::make('gambar')
                                            ->label('Gambar Opsi (Opsional)')
                                            ->image()
                                            ->directory('pertanyaan/opsi') // Disesuaikan dengan folder sebelumnya
                                            ->disk('public')
                                            ->nullable()
                                            ->maxSize(2048)
                                            ->columnSpan(2),

                                        Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar')
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, ?bool $state, Component $component): void {
                                                $current = $component->getStatePath();
                                                if ($state) {
                                                    $last = $get('opsi_benar_path');
                                                    if ($last && $last !== $current) $set($last, false);
                                                    $set('opsi_benar_path', $current);
                                                } else {
                                                    if ($get('opsi_benar_path') === $current) $set('opsi_benar_path', null);
                                                }
                                            })
                                            ->columnSpan(1),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(4)
                                    ->reorderable()
                                    ->collapsed(),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
