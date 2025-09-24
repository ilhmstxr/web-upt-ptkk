<?php

namespace App\Filament\Resources\PesertaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LampiranRelationManager extends RelationManager
{
    protected static string $relationship = 'lampiran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('no_surat_tugas')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('pas_foto')
                    ->disk('public')->directory('berkas_pendaftaran/foto')
                    ->required(),

                Forms\Components\FileUpload::make('fc_ktp')
                    ->disk('public')->directory('berkas_pendaftaran/ktp')
                    ->required(),

                Forms\Components\FileUpload::make('fc_ijazah')
                    ->disk('public')->directory('berkas_pendaftaran/ijazah')
                    ->required(),

                Forms\Components\FileUpload::make('fc_surat_tugas')
                    ->disk('public')->directory('berkas_pendaftaran/surat-tugas')
                    ->nullable(),

                Forms\Components\FileUpload::make('fc_surat_sehat')
                    ->disk('public')->directory('berkas_pendaftaran/surat-sehat')
                    ->required(),
            ]);
    }

    // Karena ini relasi HasOne (satu-ke-satu), kita tidak perlu tabel, cukup form saja.
    // Namun, kita bisa menggunakan table() untuk menampilkan action.
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat_tugas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
