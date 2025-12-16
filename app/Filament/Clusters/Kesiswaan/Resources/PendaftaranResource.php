<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;
use App\Models\PendaftaranPelatihan;
use App\Models\Kompetensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class PendaftaranResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;
    protected static ?string $cluster = Kesiswaan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel      = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';

    /**
     * ✅ Optimasi: eager load relasi yang sering dipakai di table & form
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'peserta.lampiran',
                'pelatihan',
                'kompetensiPelatihan.kompetensi',
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pendaftar')
                            ->schema([
                                // ✅ Display-only pakai Placeholder (aman saat create)
                                Forms\Components\Placeholder::make('peserta_name')
                                    ->label('Nama Peserta')
                                    ->content(fn ($record) => $record?->peserta?->nama ?? '-'),

                                Forms\Components\TextInput::make('nomor_registrasi')
                                    ->label('Nomor Registrasi')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\Placeholder::make('pelatihan_name')
                                    ->label('Pelatihan')
                                    ->content(fn ($record) => $record?->pelatihan?->nama_pelatihan ?? '-'),

                                Forms\Components\TextInput::make('kelas')
                                    ->label('Kelas')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('tanggal_pendaftaran')
                                    ->label('Tanggal Pendaftaran')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Verifikasi Berkas')
                            ->schema([
                                Forms\Components\Placeholder::make('lampiran_info')
                                    ->label('')
                                    ->content(function ($record) {
                                        $lampiran = $record?->peserta?->lampiran;

                                        if (!$lampiran) {
                                            return 'Belum ada berkas lampiran.';
                                        }

                                        // bantu safe link
                                        $ktp   = $lampiran->fc_ktp_url ?? null;
                                        $ijaz  = $lampiran->fc_ijazah_url ?? null;
                                        $sehat = $lampiran->fc_surat_sehat_url ?? null;
                                        $foto  = $lampiran->pas_foto_url ?? null;

                                        $link = fn ($url) => $url
                                            ? '<a href="'.$url.'" target="_blank" class="text-primary-600 hover:underline">Lihat File</a>'
                                            : '<span class="text-gray-500">Tidak ada</span>';

                                        return new HtmlString('
                                            <div class="grid grid-cols-2 gap-4">
                                                <div><strong>KTP:</strong> '.$link($ktp).'</div>
                                                <div><strong>Ijazah:</strong> '.$link($ijaz).'</div>
                                                <div><strong>Surat Sehat:</strong> '.$link($sehat).'</div>
                                                <div><strong>Pas Foto:</strong> '.$link($foto).'</div>
                                            </div>
                                        ');
                                    })
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status Pendaftaran')
                            ->schema([
                                Forms\Components\Select::make('status_pendaftaran')
                                    ->label('Status Pendaftaran')
                                    ->options([
                                        'Pending'    => 'Pending',
                                        'Verifikasi' => 'Verifikasi',
                                        'Diterima'   => 'Diterima',
                                        'Ditolak'    => 'Ditolak',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
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
                    ->wrap()
                    ->searchable()
                    ->sortable(),

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
                    ->color(fn (?string $state): string => match ($state) {
                        'Pending'    => 'warning',
                        'Verifikasi' => 'info',
                        'Diterima'   => 'success',
                        'Ditolak'    => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->icon('heroicon-o-calendar-days')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // ✅ option value harus sama dengan data yang disimpan
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'Pending'    => 'Pending',
                        'Verifikasi' => 'Verifikasi',
                        'Diterima'   => 'Diterima',
                        'Ditolak'    => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Filter Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(fn () => Kompetensi::query()->pluck('nama_kompetensi', 'id')->all())
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if (!$value) return $query;

                        return $query->whereHas('kompetensiPelatihan', function (Builder $q) use ($value) {
                            $q->where('kompetensi_pelatihan_id', $value);
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
            'index'  => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit'   => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
