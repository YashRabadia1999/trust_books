<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Notification;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add SMS notifications for General module
        $notifications = [
            [
                'action' => 'Birthday Wishes',
                'type' => 'sms',
                'module' => 'General',
                'permissions' => null,
            ],
            [
                'action' => 'Seasonal Greetings',
                'type' => 'sms',
                'module' => 'General',
                'permissions' => null,
            ],
            [
                'action' => 'Invoice Payment',
                'type' => 'sms',
                'module' => 'General',
                'permissions' => null,
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::updateOrCreate(
                [
                    'action' => $notification['action'],
                    'type' => $notification['type'],
                    'module' => $notification['module'],
                ],
                [
                    'permissions' => $notification['permissions'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove SMS notifications
        Notification::where('type', 'sms')
            ->whereIn('action', ['Birthday Wishes', 'Seasonal Greetings', 'Invoice Payment'])
            ->where('module', 'General')
            ->delete();
    }
};

