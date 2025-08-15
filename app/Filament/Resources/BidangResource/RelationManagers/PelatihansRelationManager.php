<?php

namespace App\Filament\Resources\BidangResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelatihansRelationManager extends RelationManager
{
    protected static string $relationship = 'pelatihans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pelatihan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bidang_nama')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_mulai')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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
