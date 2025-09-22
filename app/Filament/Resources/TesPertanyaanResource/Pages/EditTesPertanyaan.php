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
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

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

                                TextInput::make('gambar')->hidden(),

                                ViewField::make('gambar_uploader')
                                    ->label('Gambar (Opsional)')
                                    ->view('filament/components/simple-uploader')
                                    ->viewData(function (Get $get, Component $component) {
                                        $viewPath   = $component->getStatePath(); // data.gambar_uploader
                                        $targetPath = preg_replace('/\.gambar_uploader$/', '.gambar', $viewPath);

                                        $path = $get($targetPath);
                                        $existingUrl = $path ? Storage::disk('public')->url($path) : null;

                                        return [
                                            'targetPath'  => $targetPath,
                                            'existingUrl' => $existingUrl,
                                            'fieldName'   => 'gambar',
                                            'folder'      => 'pertanyaan',
                                            'uploadMeta'  => [],
                                        ];
                                    }),
                            ])
                            ->columns(2),
                        Section::make('Opsi Jawaban')
                            ->description('Kelola pilihan jawaban untuk pertanyaan ini.')
                            ->visible(fn (Get $get): bool => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert']))
                            ->schema([
                                Hidden::make('opsi_benar_path')->dehydrated(false),

                                Repeater::make('opsiJawabans')
                                    ->relationship('opsiJawabans')
                                    ->label('Daftar Opsi')
                                    ->afterStateHydrated(function (Get $get, Set $set, ?array $state) {
                                        foreach (array_keys($state ?? []) as $key) {
                                            if ($get("opsiJawabans.$key.apakah_benar")) {
                                                $set('opsi_benar_path', "opsiJawabans.$key.apakah_benar");
                                                break;
                                            }
                                        }
                                    })
                                    ->mutateRelationshipDataBeforeCreateUsing(fn (array $data) => \Illuminate\Support\Arr::only($data, ['teks_opsi', 'gambar', 'apakah_benar']))
                                    ->mutateRelationshipDataBeforeSaveUsing(fn (array $data) => \Illuminate\Support\Arr::only($data, ['teks_opsi', 'gambar', 'apakah_benar']))
                                    ->schema([
                                        Textarea::make('teks_opsi')
                                            ->label('Teks Opsi')
                                            ->rows(2)
                                            ->required()
                                            ->columnSpan(2),

                                        TextInput::make('gambar')->hidden(),

                                        ViewField::make('gambar_uploader')
                                            ->label('Gambar Opsi (Opsional)')
                                            ->view('filament/components/simple-uploader')
                                            ->viewData(function (Get $get, Component $component) {
                                                $viewPath   = $component->getStatePath(); // ...opsiJawabans.{key}.gambar_uploader
                                                $targetPath = preg_replace('/\.gambar_uploader$/', '.gambar', $viewPath);

                                                $path = $get($targetPath);
                                                $existingUrl = $path ? Storage::disk('public')->url($path) : null;

                                                return [
                                                    'targetPath'  => $targetPath,
                                                    'existingUrl' => $existingUrl,
                                                    'fieldName'   => 'gambar',
                                                    'folder'      => 'pertanyaan/opsi',
                                                    'uploadMeta'  => [
                                                        'peserta_id'  => $get('peserta_id'),
                                                        'bidang_id'   => $get('bidang_id'),
                                                        'instansi_id' => $get('instansi_id'),
                                                    ],
                                                ];
                                            })
                                            ->columnSpan(2),

                                        Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar')
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get, ?bool $state, Component $component): void {
                                                $current = $component->getStatePath();

                                                if ($state) {
                                                    $last = $get('opsi_benar_path');
                                                    if ($last && $last !== $current) {
                                                        $set($last, false);
                                                    }

                                                    $set('opsi_benar_path', $current);

                                                    return;
                                                }

                                                if ($get('opsi_benar_path') === $current) {
                                                    $set('opsi_benar_path', null);
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
