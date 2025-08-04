<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterCreate(): void
    {
        if ($this->record && request()->has('data.permissions')) {
            $this->record->permissions()->sync(request('data.permissions'));
        }
    }
}
