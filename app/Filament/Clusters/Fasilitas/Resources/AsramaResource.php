<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages\PembagianKamar; // LOKASI BARU: Di dalam Pages Resource
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets\AsramaStatsOverview; // Import Widget
use App\Models\Asrama;
use App\Models\Pelatihan;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;

    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    /**
     * Form untuk create / edit Asrama (menjadi lebih kompleks dengan Kapasitas, PIC, dan Status Aktif).
     */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Asrama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('gender')
                            ->label('Khusus Gender')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan'  => 'Perempuan',
                                'Campur'     => 'Campur',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('kapasitas')
                            ->label('Kapasitas Maksimal (Orang)')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),

                        Forms\Components\TextInput::make('kontak_pic')
                            ->label('Kontak PIC Asrama')
                            ->nullable()
                            ->tel()
                            ->maxLength(255)
                            ->helperText('Nomor telepon atau kontak penanggung jawab.'),
                        
                        Forms\Components\Textarea::make('alamat')->label('Alamat'),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika asrama sedang dalam perbaikan atau tidak digunakan.'),
                            
                    ])->columns(2),
            ]);
    }

    /**
     * Table columns, filters, dan actions (menjadi lebih kompleks).
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Asrama')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Asrama $record): string => $record->alamat ? \Str::limit($record->alamat, 50) : 'Tidak ada alamat'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Khusus')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelatihans_count')
                    ->label('Jml Pelatihan')
                    ->counts('pelatihans')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamars_count')
                    ->label('Jml Kamar')
                    ->counts('kamars')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                // Filter baru untuk gender dan status aktif
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan'  => 'Perempuan',
                        'Campur'     => 'Campur',
                    ])
                    ->label('Filter Gender'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Asrama')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif')
                    ->indicator('Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                // Aksi kustom untuk mengubah status aktif
                Tables\Actions\Action::make('toggle_status')
                    ->icon(fn (Asrama $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->label(fn (Asrama $record): string => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->requiresConfirmation()
                    ->action(function (Asrama $record) {
                        $record->is_active = !$record->is_active;
                        $record->save();
                        Notification::make()
                            ->title('Status berhasil diperbarui')
                            ->success()
                            ->send();
                    })
                    ->color(fn (Asrama $record): string => $record->is_active ? 'danger' : 'success'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarRelationManager::class,
            RelationManagers\PelatihansRelationManager::class,
        ];
    }
    
    /**
     * Daftarkan widget untuk ditampilkan di halaman Resource.
     */
    public static function getWidgets(): array
    {
        return [
            AsramaStatsOverview::class,
        ];
    }

    /**
     * Menambahkan Page PembagianKamar ke dalam navigasi Cluster Fasilitas.
     */
    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListAsramas::route('/'),
            'create'     => Pages\CreateAsrama::route('/create'),
            'edit'       => Pages\EditAsrama::route('/{record}/edit'),
            
            // Tautan ke Page Pembagian Kamar
          //  'pembagian' => Pages\PembagianKamar::route('/pembagian'),
        ];
    }
    
    /**
     * Definisikan widget yang muncul di header ListAsramas.
     */
    public static function getHeaderWidgets(): array
    {
        return [
            AsramaStatsOverview::class,
        ];
    }
}