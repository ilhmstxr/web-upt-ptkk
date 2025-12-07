<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;
use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendaftaranResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;
    protected static ?string $cluster = Kesiswaan::class;

    // ✅ Hero icon + label biar rapih di sidebar
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Pendaftaran Pelatihan';
    protected static ?string $modelLabel      = 'Pendaftaran';
    protected static ?string $pluralModelLabel = 'Pendaftaran Pelatihan';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pendaftar')
                            ->schema([
                                Forms\Components\TextInput::make('peserta_name')
                                    ->label('Nama Peserta')
                                    ->formatStateUsing(fn ($record) => $record->peserta->nama ?? '-')
                                    ->disabled(),

                                Forms\Components\TextInput::make('nomor_registrasi')
                                    ->label('Nomor Registrasi')
                                    ->disabled(),

                                Forms\Components\TextInput::make('pelatihan_name')
                                    ->label('Pelatihan')
                                    ->formatStateUsing(fn ($record) => $record->pelatihan->nama_pelatihan ?? '-')
                                    ->disabled(),

                                // ✅ tambah kelas di form
                                Forms\Components\TextInput::make('kelas')
                                    ->label('Kelas')
                                    ->disabled(),

                                Forms\Components\TextInput::make('tanggal_pendaftaran')
                                    ->label('Tanggal Pendaftaran')
                                    ->disabled(),
                            ])->columns(2),

                        Forms\Components\Section::make('Verifikasi Berkas')
                            ->schema([
                                Forms\Components\Placeholder::make('lampiran_info')
                                    ->content(function ($record) {
                                        if (!$record || !$record->peserta || !$record->peserta->lampiran) {
                                            return 'Belum ada berkas lampiran.';
                                        }
                                        $lampiran = $record->peserta->lampiran;
                                        return new \Illuminate\Support\HtmlString('
                                            <div class="grid grid-cols-2 gap-4">
                                                <div><strong>KTP:</strong> <a href="'.$lampiran->fc_ktp_url.'" target="_blank" class="text-primary-600 hover:underline">Lihat File</a></div>
                                                <div><strong>Ijazah:</strong> <a href="'.$lampiran->fc_ijazah_url.'" target="_blank" class="text-primary-600 hover:underline">Lihat File</a></div>
                                                <div><strong>Surat Sehat:</strong> <a href="'.$lampiran->fc_surat_sehat_url.'" target="_blank" class="text-primary-600 hover:underline">Lihat File</a></div>
                                                <div><strong>Pas Foto:</strong> <a href="'.$lampiran->pas_foto_url.'" target="_blank" class="text-primary-600 hover:underline">Lihat File</a></div>
                                            </div>
                                        ');
                                    })
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status Pendaftaran')
                            ->schema([
                                Forms\Components\Select::make('status_pendaftaran')
                                    ->label('Status Pendaftaran')
                                    ->options([
                                        'Pending' => 'Pending',
                                        'Verifikasi' => 'Verifikasi',
                                        'Diterima' => 'Diterima',
                                        'Ditolak' => 'Ditolak',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->icon('heroicon-o-identification')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->icon('heroicon-o-academic-cap')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '-';
                        $words = explode(' ', $state);
                        $chunks = array_chunk($words, 4);
                        return implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), $chunks));
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),

                // ✅ kolom KELAS di tabel
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->icon('heroicon-o-building-office-2')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->icon('heroicon-o-shield-check')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Verifikasi' => 'info',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->icon('heroicon-o-calendar-days')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Verifikasi' => 'Verifikasi',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('pelatihan')
                    ->label('Filter Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('kompetensi')
                    ->label('Kompetensi')
                    ->options(fn () => \App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) return $query;

                        return $query->whereHas('kompetensiPelatihan', function ($q) use ($data) {
                            $q->where('kompetensi_id', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
