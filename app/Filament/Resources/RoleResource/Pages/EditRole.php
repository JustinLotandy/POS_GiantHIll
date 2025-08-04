<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterSave(): void
    {
        if ($this->record && request()->has('data.permissions')) {
            $this->record->permissions()->sync(request('data.permissions'));
        }
    }
}
