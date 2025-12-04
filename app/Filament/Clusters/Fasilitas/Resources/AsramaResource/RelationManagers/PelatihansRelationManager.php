<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;


class PelatihansRelationManager extends RelationManager
{
    protected static string $relationship = 'pelatihans';
    protected static ?string $recordTitleAttribute = 'nama_pelatihan';

    /**
     * Table untuk menampilkan Pelatihan yang berelasi dengan Asrama.
     *
     * @param  \Filament\Tables\Table  $table
     * @return \Filament\Tables\Table
     */
    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_program')
                    ->label('Jenis Program')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
