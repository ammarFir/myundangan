<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;


class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pembayaran';

    public static function canCreate(): bool
    {
        return false;
    } 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                ->numeric(),

                Forms\Components\Select::make('status') 
                ->options([
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ])
                ->required(),
                //

                Forms\Components\Textarea::make('catatan')
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('undangan.nama_pria')
                ->label('Undangan')
                ->formatStateUsing(fn ($state, $record) => $record->undangan->nama_pria . ' & ' . $record->undangan->nama_wanita)
                ->searchable(),

                Tables\Columns\ImageColumn::make('bukti')
                ->disk('public')
                ->height(60)
                ->url(fn ($record) => Storage::url($record->bukti))
                ->openUrlInNewTab(),

            Tables\Columns\TextColumn::make('nominal')
            ->label('Nominal')
            ->money('IDR', locale: 'id')
            ->placeholder('-'),

            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color(fn (string $state): string => match  ($state) {
                'menunggu' => 'warning',
                'disetujui' => 'success',
                'ditolak' => 'danger',
                default => 'gray'
            }),

            Tables\Columns\TextColumn::make('catatan')
            ->limit(40)
            ->placeholder('-'),

            Tables\Columns\TextColumn::make('created_at')
            ->label('Dikirim')
            ->dateTime()
            ->sortable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options(
                    [
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        
                        ]
                        )
            ])


            ->actions([
                Tables\Actions\Action::make('setujui')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Pembayaran $record)=> $record->status === 'menunggu')
                ->action(function (Pembayaran $record){
                    $record->update(['status' =>'disetujui', 'catatan' => null]);
                    $record->undangan()->update(['status' => 'active' ]);
                }),

                Tables\Actions\Action::make('tolak')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (Pembayaran $record) => $record->status === 'menunggu')
                ->form([
                    Forms\Components\Textarea::make('catatan')
                    ->label('Alasan Penolakan')
                    ->required(),
                ])
                ->action(function (Pembayaran $record, array $data) {
                    $record->update(['status' => 'ditolak', 'catatan' => $data['catatan']]);
                    $record->undangan()->update(['status' => 'draft']);
                })
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
