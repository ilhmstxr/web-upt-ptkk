<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\RelationManagers;
use App\Models\SorotanPelatihan;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SorotanPelatihanResource extends Resource
{
    protected static ?string $model = SorotanPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationLabel = "Sorotan Pelatihan (4-8 Foto)";

    protected static ?string $modelLabel = 'Sorotan Pelatihan (4-8 Foto)'; // Label tunggal (misal: "Tambah Sorotan Pelatihan")

    protected static ?string $pluralModelLabel = 'Sorotan Pelatihan (4-8 Foto)'; // Label jamak/judul halaman (mengganti "Sorotan Pelatihans")

  public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Select::make('kelas')
            ->label('Jenis Sorotan')
            ->options([
                'mtu'        => 'Mobil Training Unit',
                'reguler'    => 'Pelatihan Reguler',
                'akselerasi' => 'Pelatihan Akselerasi',
            ])
            ->required()
            ->unique(ignoreRecord: true),

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

        // 🔥 Upload foto maksimal 6
        Forms\Components\FileUpload::make('photos')
            ->label('Foto Sorotan (maksimal 6)')
            ->image()
            ->multiple(6)
            ->maxFiles(6)          // ⬅️ BATAS MAKS 6
            ->directory('sorotan_pelatihan')
            ->reorderable()        // bisa atur urutan
            ->required()
            ->columnSpanFull(),
    ]);
}

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('kelas')
                ->label('Kelas')
                ->formatStateUsing(fn ($state) => match($state) {
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
            'index' => Pages\ListSorotanPelatihans::route('/'),
            'create' => Pages\CreateSorotanPelatihan::route('/create'),
            'edit' => Pages\EditSorotanPelatihan::route('/{record}/edit'),
        ];
    }
}
