<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // public function afterDelete(): void
    // {
    //     $user = $this->record;

    //     $adminUsers = \App\Models\User::where('role', 'super_admin')->get();
    //     foreach ($adminUsers as $admin) {
    //         \App\Models\Notification::create([
    //             'user_id' => $admin->id,
    //             'type' => 'warning',
    //             'message' => "User '{$user->name}' telah dihapus.",
    //             'status' => 'unread',
    //         ]);
    //     }
    // }
}
