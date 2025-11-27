<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource\Pages;
use App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource\RelationManagers;
use App\Models\ProfilUPT;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfilUPTResource extends Resource
{
    protected static ?string $model = ProfilUPT::class;

    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profil Kepala UPT')
                    ->schema([
                        Forms\Components\TextInput::make('kepala_upt_name')
                            ->label('Nama Kepala UPT')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('kepala_upt_photo')
                            ->label('Foto Kepala UPT')
                            ->image()
                            ->directory('profil')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('sambutan')
                            ->label('Kata Sambutan')
                            ->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Section::make('Tentang UPT')
                    ->schema([
                        Forms\Components\RichEditor::make('sejarah')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('visi')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('misi')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('alamat')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('kepala_upt_photo')
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('kepala_upt_name')
                    ->label('Kepala UPT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfilUPTS::route('/'),
            'create' => Pages\CreateProfilUPT::route('/create'),
            'edit' => Pages\EditProfilUPT::route('/{record}/edit'),
        ];
    }
}
