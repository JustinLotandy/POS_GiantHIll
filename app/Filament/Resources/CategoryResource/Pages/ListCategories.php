<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('kosongkanKategori')
                ->label('Kosongkan Kategori')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \App\Models\Category::truncate(); // Hapus semua data kategori
                    // Tambahkan notifikasi jika ingin
                    session()->flash('notification', 'Semua kategori berhasil dihapus!');
                }),
        ];
    }
}
