<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;
use App\Models\SorotanPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SorotanPelatihanResource extends Resource
{
    protected static ?string $model = SorotanPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationLabel = "Sorotan Pelatihan (4-8 Foto)";
    protected static ?string $modelLabel = 'Sorotan Pelatihan (4-8 Foto)';
    protected static ?string $pluralModelLabel = 'Sorotan Pelatihan (4-8 Foto)';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kelas')
                ->label('Kelas')
                ->required()
                ->options([
                    'mtu'        => 'Mobil Training Unit',
                    'reguler'    => 'Program Reguler',
                    'akselerasi' => 'Program Akselerasi',
                ])
                ->native(false)
                ->placeholder('Pilih kelas'),

            Forms\Components\TextInput::make('title')
                ->label('Judul (opsional, bisa override default)')
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi (opsional, bisa override default)')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_published')
                ->label('Publish')
                ->default(true),

            // 🔥 Upload foto slider (fix ke 6 gambar)
            Forms\Components\FileUpload::make('photos')
                ->label('Foto Slider (6 gambar)')
                ->disk('public') // ⬅ simpan di storage/app/public
                ->directory('konten-website/sorotan-pelatihan') // ⬅ folder khusus sorotan
                ->multiple()
                ->reorderable()
                ->minFiles(6)
                ->maxFiles(6)
                ->image()
                ->maxSize(4096) // KB
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'mtu'        => 'Mobil Training Unit',
                        'reguler'    => 'Pelatihan Reguler',
                        'akselerasi' => 'Pelatihan Akselerasi',
                        default      => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Publish'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListSorotanPelatihans::route('/'),
            'create' => Pages\CreateSorotanPelatihan::route('/create'),
            'edit'   => Pages\EditSorotanPelatihan::route('/{record}/edit'),
        ];
    }
}
