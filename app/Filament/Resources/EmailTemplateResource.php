<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Models\EmailTemplate;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn; // Import class ini
use Filament\Tables\Table;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope'; // Icon diganti agar lebih sesuai
    protected static ?string $navigationLabel = 'Template Email';
    protected static ?string $slug = 'template-email';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->label('Nama Template Surat')
                    ->required()
                    ->unique(EmailTemplate::class, 'slug', ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->helperText('Gunakan sebagai pengenal unik untuk template (contoh: notifikasi-sertifikat).'),
                TextInput::make('subject')
                    ->label('Subjek Surat')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->helperText('Subjek email. Anda bisa menggunakan placeholder seperti [nama_peserta] atau [nama_pelatihan].'),
                RichEditor::make('body')
                    ->label('Isi Surat')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Isi email dalam format HTML. Anda bisa menggunakan placeholder yang sama.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        // REVISI: Melengkapi kolom pada tabel
        return $table
            ->columns([
                TextColumn::make('slug')->label('Nama Template')->searchable(),
                TextColumn::make('subject')->label('Subjek Email')->limit(50),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime()->sortable(),
            ])
            ->filters([
                //
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

    // ... (sisa kode tidak berubah)
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}