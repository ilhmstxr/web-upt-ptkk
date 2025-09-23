<?php

namespace App\Filament\Resources\TesPertanyaanResource\Pages;

use App\Filament\Resources\TesPertanyaanResource;
use App\Http\Controllers\PertanyaanController;
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
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Arr;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
                            ->visible(fn(Get $get): bool => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert']))
                            ->schema([
                                Hidden::make('opsi_benar_path')->dehydrated(false),

                                Repeater::make('opsiJawabans')
                                    ->relationship('opsiJawabans')
                                    ->label('Daftar Opsi')
                                    // simpan hanya kolom yang dibutuhkan
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
                                            ->columnSpan(2),

                                        // path file yang disimpan ke DB
                                        TextInput::make('gambar')->hidden(),

                                        // uploader custom → panggil UploadController
                                        ViewField::make('gambar_uploader')
                                            ->label('Gambar Opsi (Opsional)')
                                            ->view('filament/components/simple-uploader')
                                            ->viewData(function (Get $get, Component $component) {
                                                $viewPath   = $component->getStatePath();              // ...opsiJawabans.{key}.gambar_uploader
                                                $targetPath = preg_replace('/\.gambar_uploader$/', '.gambar', $viewPath);

                                                $path       = $get($targetPath);
                                                $existing   = $path ? Storage::disk('public')->url($path) : null;

                                                return [
                                                    'targetPath'  => $targetPath,
                                                    'existingUrl' => $existing,
                                                    'fieldName'   => 'gambar',
                                                    'folder'      => 'pertanyaan/opsi',
                                                    // jika perlu pola penamaan seperti Lampiran, isi dari form utama
                                                    'uploadMeta'  => [
                                                        'peserta_id'  => $get('peserta_id') ?? null,
                                                        'bidang_id'   => $get('bidang_id') ?? null,
                                                        'instansi_id' => $get('instansi_id') ?? null,
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

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Group::make()->schema([
    //                 Section::make('Detail Pertanyaan')
    //                     ->schema([
    //                         TextInput::make('nomor')->numeric()->minValue(1)->required()->label('Nomor'),
    //                         Textarea::make('teks_pertanyaan')->rows(3)->required()->label('Teks Pertanyaan'),
    //                         Select::make('tipe_jawaban')
    //                             ->options([
    //                                 'pilihan_ganda' => 'Pilihan Ganda',
    //                                 'skala_likert'  => 'Skala Likert',
    //                                 'esai'          => 'Esai',
    //                             ])
    //                             ->required()
    //                             ->native(false)
    //                             ->label('Tipe Jawaban'),
    //                         Hidden::make('opsi_benar_path')->default(null),
    //                     ])->columns(1),

    //                 Section::make('Opsi Jawaban')
    //                     ->visible(fn(Forms\Get $get) => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert'], true))
    //                     ->schema([
    //                         Repeater::make('opsiJawabans')
    //                             ->relationship('opsiJawabans')
    //                             ->reorderable(true)
    //                             ->defaultItems(4)
    //                             ->schema([
    //                                 Textarea::make('teks_opsi')->rows(2)->required()->label('Teks Opsi'),

    //                                 // kolom path gambar yang disimpan ke DB
    //                                 Hidden::make('gambar'),

    //                                 // uploader kustom berbasis blade + Alpine
    //                                 ViewField::make('uploader')
    //                                     ->view('filament/components/simple-uploader')
    //                                     ->viewData([
    //                                         // statePath dinamis: path field 'gambar' di repeater
    //                                         'statePath' => null, // akan ditangani oleh JS melalui $wire.set pada field 'gambar'
    //                                         'folder'    => 'pertanyaan/opsi',
    //                                     ])
    //                                     ->label('Gambar (opsional)'),

    //                                 Toggle::make('apakah_benar')
    //                                     ->label('Tandai Sebagai Benar')
    //                                     ->onIcon('heroicon-o-check-circle')
    //                                     ->offIcon('heroicon-o-x-circle')
    //                                     ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
    //                                         // Pastikan hanya satu opsi benar untuk pilihan_ganda
    //                                         if (! $state) {
    //                                             return;
    //                                         }

    //                                         if ($get('../../tipe_jawaban') !== 'pilihan_ganda') {
    //                                             return;
    //                                         }

    //                                         // set semua item lain ke false
    //                                         $items = $get('../..opsiJawabans') ?? [];
    //                                         foreach ($items as $index => $item) {
    //                                             if ((bool)Arr::get($item, 'apakah_benar') && $index !== $get('../..repeater.index')) {
    //                                                 $set("../../opsiJawabans.{$index}.apakah_benar", false);
    //                                             }
    //                                         }
    //                                     }),
    //                             ])
    //                             ->columns(2),
    //                     ])->collapsed(),
    //             ])->columns(1),
    //         ]);
    // }

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $request = Request::create('/', 'PUT', $data);
    //     $request = Request::createFromBase($request);
    //     $request->setUserResolver(fn() => auth()->user());

    //     /** @var PertanyaanController $controller */
    //     $controller = app(PertanyaanController::class);
    //     $updated = $controller->update($request, $record);

    //     if ($updated instanceof Model) {
    //         return $updated;
    //     }

    //     return $record->fresh();
    // }

    // protected function beforeSave(): void
    // {
    //     $data = $this->form->getState();
    //     $tipe = $data['tipe_jawaban'] ?? null;
    //     $opsi = $data['opsiJawabans'] ?? [];

    //     if ($tipe === 'pilihan_ganda') {
    //         $benar = collect($opsi)->where('apakah_benar', true)->count();
    //         if ($benar !== 1) {
    //             throw ValidationException::withMessages([
    //                 'opsiJawabans' => 'Untuk tipe pilihan_ganda wajib tepat 1 opsi benar.',
    //             ]);
    //         }
    //     }

    //     if ($tipe === 'esai') {
    //         // kosongkan opsi jika tipe esai
    //         $this->form->fill(['opsiJawabans' => []]);
    //     }
    // }
}
