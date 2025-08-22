<?php

namespace App\Filament\Resources\PelatihanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesertasRelationManager extends RelationManager
{
    protected static string $relationship = 'pesertas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tanggal_lahir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_kelamin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('agama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instansi.asal_instansi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instansi.alamat_instansi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instansi.bidang_keahlian')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instansi.kelas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instansi.cabang_dinas_wilayah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lampiran.no_surat_tugas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('lampiran.fc_ktp')
                    ->required()
                    ->directory('berkas_pendaftaran/ktp')
                    ->image(),
                Forms\Components\FileUpload::make('lampiran.fc_ijazah')
                    ->required()
                    ->directory('berkas_pendaftaran/ijazah')
                    ->image(),
                Forms\Components\FileUpload::make('lampiran.fc_surat_tugas')
                    ->required()
                    ->directory('berkas_pendaftaran/surat-tugas')
                    ->image(),
                Forms\Components\FileUpload::make('lampiran.fc_surat_sehat')
                    ->required()
                    ->directory('berkas_pendaftaran/foto')
                    ->image(),
                Forms\Components\FileUpload::make('lampiran.pas_foto')
                    ->required()
                    ->image(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                // Menampilkan nama peserta dan asal instansinya
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')
                    ->label('Asal Instansi') // Memberi label yang lebih jelas
                    ->searchable(),
                Tables\Columns\TextColumn::make('instansi.cabang_dinas_wilayah')
                    ->label('Cabang Dinas Wilayah') // Memberi label yang lebih jelas
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
