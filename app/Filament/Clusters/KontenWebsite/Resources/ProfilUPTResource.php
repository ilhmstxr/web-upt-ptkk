<?php  
  
namespace App\Filament\Clusters\KontenWebsite\Resources;  
  
use App\Filament\Clusters\KontenWebsite;  
// ✅ PERBAIKAN: Baris import Pages harus ada  
use App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource\Pages;   
use App\Models\ProfilUPT;  
use Filament\Forms;  
use Filament\Forms\Form;  
use Filament\Resources\Resource;  
use Filament\Tables;  
use Filament\Tables\Table;  
use Illuminate\Database\Eloquent\Builder;  
  
class ProfilUPTResource extends Resource  
{  
    protected static ?string $model = ProfilUPT::class;  
  
    protected static ?string $cluster = KontenWebsite::class;  
  
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';  
    protected static ?string $modelLabel = 'Profil UPT';  
    protected static ?string $navigationLabel = 'Profil UPT';  
    protected static ?string $pluralModelLabel = 'Daftar Profil UPT';  
    protected static ?int $navigationSort = 3;  
  
    public static function form(Form $form): Form  
    {  
        return $form  
            ->schema([  
                Forms\Components\Tabs::make('Profil UPT')  
                    ->tabs([  
                        Forms\Components\Tabs\Tab::make('Kepala UPT & Sambutan')  
                            ->icon('heroicon-o-user')  
                            ->columns(2)  
                            ->schema([  
                                Forms\Components\TextInput::make('kepala_upt_name')  
                                    ->label('Nama Kepala UPT')  
                                    ->required()  
                                    ->maxLength(255)  
                                    ->columnSpan(1),  
  
                                Forms\Components\FileUpload::make('kepala_upt_photo')  
                                    ->label('Foto Kepala UPT')  
                                    ->image()  
                                    ->directory('profil')  
                                    ->disk('public')  
                                    ->columnSpan(1),  
  
                                Forms\Components\Textarea::make('sambutan')  
                                    ->label('Kata Sambutan')  
                                    ->rows(5)  
                                    ->columnSpanFull(),  
                            ]),  
                          
                        Forms\Components\Tabs\Tab::make('Sejarah & Visi Misi')  
                            ->icon('heroicon-o-book-open')  
                            ->schema([  
                                Forms\Components\RichEditor::make('sejarah')  
                                    ->label('Sejarah UPT')  
                                    ->columnSpanFull(),  
                                Forms\Components\RichEditor::make('visi')  
                                    ->label('Visi UPT')  
                                    ->columnSpanFull(),  
                                Forms\Components\RichEditor::make('misi')  
                                    ->label('Misi UPT')  
                                    ->columnSpanFull(),  
                            ]),  
  
                        Forms\Components\Tabs\Tab::make('Kontak')  
                            ->icon('heroicon-o-phone')  
                            ->columns(3)  
                            ->schema([  
                                Forms\Components\TextInput::make('alamat')  
                                    ->label('Alamat Lengkap')  
                                    ->maxLength(255)  
                                    ->columnSpan(3),  
                                Forms\Components\TextInput::make('email')  
                                    ->label('Email Kontak')  
                                    ->email()  
                                    ->maxLength(255),  
                                Forms\Components\TextInput::make('phone')  
                                    ->label('Nomor Telepon')  
                                    ->tel()  
                                    ->maxLength(255),  
                            ]),  
                    ]),  
            ]);  
    }  
  
    public static function table(Table $table): Table  
    {  
        return $table  
            ->columns([  
                Tables\Columns\ImageColumn::make('kepala_upt_photo')  
                    ->label('Foto')  
                    ->size(50),  
                      
                Tables\Columns\TextColumn::make('kepala_upt_name')  
                    ->label('Kepala UPT')  
                    ->searchable()  
                    ->sortable(),  
                      
                Tables\Columns\TextColumn::make('email')  
                    ->label('Email')  
                    ->searchable(),  
                      
                Tables\Columns\TextColumn::make('phone')  
                    ->label('Telepon')  
                    ->searchable(),  
                      
                Tables\Columns\TextColumn::make('updated_at')  
                    ->label('Terakhir Diubah')  
                    ->dateTime('d M Y H:i')  
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