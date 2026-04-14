<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // public function afterCreate(): void
    // {
    //     $user = $this->record;

    //     $adminUsers = \App\Models\User::where('role', 'super_admin')->get();
    //     foreach ($adminUsers as $admin) {
    //         Notification::create([
    //             'user_id' => $admin->id,
    //             'type' => 'info',
    //             'message' => "User baru '{$user->name}' telah terdaftar.",
    //             'status' => 'unread',
    //         ]);
    //     }
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
