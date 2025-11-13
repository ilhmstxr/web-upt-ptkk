<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SendEmailResource\Pages;
use App\Models\EmailLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// Sebaiknya ganti nama class menjadi EmailLogResource
class SendEmailResource extends Resource
{
    protected static ?string $model = EmailLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';
    protected static ?string $navigationLabel = 'Log Email';
    protected static ?string $slug = 'log-email';


    // Kita tidak perlu form untuk membuat log secara manual
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sent_at')->label('Waktu Kirim')->dateTime()->sortable(),
                TextColumn::make('email')->label('Penerima')->searchable(),
                TextColumn::make('subject')->label('Subjek')->limit(40),
                IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'sent' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Aksi untuk melihat detail email jika perlu
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]) // Biasanya log tidak perlu bulk action
            ->defaultSort('sent_at', 'desc'); // Urutkan berdasarkan yang terbaru
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSendEmails::route('/'),
            // Hapus halaman create dan edit karena log dibuat otomatis
        ];
    }

    // Mencegah pembuatan record baru dari UI
    public static function canCreate(): bool
    {
        return false;
    }
}