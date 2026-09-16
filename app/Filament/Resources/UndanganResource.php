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

                Forms\Components\DatePicker::make('tanggal_acara'),
                Forms\Components\TimePicker::make('waktu_acara'),

                Forms\Components\TextInput::make('lokasi')
                    ->maxLength(255),

                Forms\Components\TextInput::make('alamat')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('link_maps')
                    ->url()
                    ->maxLength(255),

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

                Tables\Columns\TextColumn::make('tanggal_acara')
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