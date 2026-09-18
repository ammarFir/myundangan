<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UndanganResource\Pages;
use App\Filament\Resources\UndanganResource\RelationManagers;
use App\Models\Undangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UndanganResource extends Resource
{
    protected static ?string $model = Undangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //dropdwon
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name') // tampilkan kolom name dari relasi user
                    ->searchable() // bisa dicari kalau user nya banyak
                    ->required(),

                Forms\Components\TextInput::make('nama_pria')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nama_wanita')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('tema')
                    ->maxLength(255),



                                    Forms\Components\Section::make('Akad Nikah')
                    ->schema([
                        Forms\Components\DatePicker::make('akad_tanggal'),
                        Forms\Components\TimePicker::make('akad_waktu_mulai'),
                        Forms\Components\TimePicker::make('akad_waktu_selesai'),
                        Forms\Components\TextInput::make('akad_lokasi')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('akad_alamat')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('akad_link_maps')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Resepsi')
                    ->schema([
                        Forms\Components\DatePicker::make('resepsi_tanggal'),
                        Forms\Components\TimePicker::make('resepsi_waktu_mulai'),
                        Forms\Components\TimePicker::make('resepsi_waktu_selesai'),
                        Forms\Components\TextInput::make('resepsi_lokasi')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('resepsi_alamat')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('resepsi_link_maps')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\TextInput::make('link_streaming')
                    ->url()
                    ->maxLength(255),

                // field ayat/kutipan, ditaro sebelum status
                Forms\Components\Textarea::make('ayat_teks')
                    ->label('Teks Ayat/Kutipan')
                    ->columnSpanFull(), // lebar penuh biar nyaman nulis teks panjang

                Forms\Components\Textarea::make('ayat_arti')
                    ->label('Arti/Terjemahan')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('ayat_sumber')
                    ->label('Sumber (contoh: Ar-Rum: 21)')
                    ->maxLength(255),

                // Section = bikin kotak berjudul, biar field mempelai pria & wanita gak campur aduk
                Forms\Components\Section::make('Mempelai Pria')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap_pria')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('anak_ke_pria')
                            ->label('Anak ke berapa')
                            ->placeholder('Contoh: Putra pertama')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orang_tua_pria')
                            ->label('Dari pasangan')
                            ->placeholder('Contoh: Bapak Slamet & Ibu Sari')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('instagram_pria')
                            ->label('Instagram (opsional)')
                            ->maxLength(255),

                        // FileUpload khusus buat gambar, otomatis kasih preview
                        Forms\Components\FileUpload::make('foto_pria')
                            ->image() // cuma nerima file gambar
                            ->directory('mempelai') // disimpan di storage/app/public/mempelai
                            ->imagePreviewHeight('150'),
                    ])
                    ->columns(2), // field di dalam section ini disusun 2 kolom

                Forms\Components\Section::make('Mempelai Wanita')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap_wanita')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('anak_ke_wanita')
                            ->label('Anak ke berapa')
                            ->placeholder('Contoh: Putri kedua')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orang_tua_wanita')
                            ->label('Dari pasangan')
                            ->placeholder('Contoh: Bapak Ahmad & Ibu Wati')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('instagram_wanita')
                            ->label('Instagram (opsional)')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('foto_wanita')
                            ->image()
                            ->directory('mempelai')
                            ->imagePreviewHeight('150'),
                    ])
                    ->columns(2),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_payment' => 'Menunggu Pembayaran',
                        'active' => 'Aktif',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pria')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_wanita')
                    ->searchable(),

                              Tables\Columns\TextColumn::make('akad_tanggal')
                    ->label('Tanggal Akad')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'pending_payment',
                        'success' => 'active',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_payment' => 'Menunggu Pembayaran',
                        'active' => 'Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUndangans::route('/'),
            'create' => Pages\CreateUndangan::route('/create'),
            'edit' => Pages\EditUndangan::route('/{record}/edit'),
        ];
    }
}