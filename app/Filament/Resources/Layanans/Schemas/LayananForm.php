<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Form;
class LayananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'name')
                ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Layanan')
                    ->multiple() // memungkinkan upload banyak file
                    ->directory('layanans') // simpan di storage/app/public/layanans
                    ->reorderable() // bisa ubah urutan gambar
                    ->image() // tampilkan preview gambar
                    ->maxFiles(5) // batas jumlah gambar
                    ->columnSpanFull(),

                TextInput::make('estimasi_menit')
                    ->required()
                    ->numeric(),
            ]);
    }
}
