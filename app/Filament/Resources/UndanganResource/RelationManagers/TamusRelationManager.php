<?php

namespace App\Filament\Resources\UndanganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TamusRelationManager extends RelationManager
{
    protected static string $relationship = 'tamus';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('kehadiran')
                ->options([
                    'hadir' => 'Hadir',
                    'tidak_hadir' => 'Tidak Hadir',
                    'ragu_ragu' => 'Ragu-ragu',
                ]),

                Forms\Components\TextInput::make('jumlah_orang')
                ->numeric(), //cuma nerima angka

                Forms\Components\Textarea::make('pesan')
                ->label('Ucapan/Pesan')
                ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                ->searchable(),

                //badge biar kellihatan warna beda tiap status kehadiran
                Tables\Columns\BadgeColumn::make('kehadiran')
                ->colors([
                    'success' => 'hadir',
                    'danger' => 'tidak_hadir',
                    'warning' => 'ragu_ragu'
                ]),

                Tables\Columns\TextColumn::make('jumlah_orang')
                ->label('Jumlah'),


                Tables\Columns\TextColumn::make('pesan')
                ->label('Ucapan')
                ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                ->label('Waktu')
                ->dateTime()
                ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kehadiran')
                ->options([
                    'hadir' => 'Hadir',
                    'tidak_hadir' => 'Tidak Hadir',
                    'ragu_ragu' => 'Ragu-ragu',
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
