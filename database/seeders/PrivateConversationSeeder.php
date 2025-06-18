<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrivateConversationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Danh sách các cặp user cần tạo hội thoại 1-1
        $userPairs = [
            [
                'user1' => '0dddb9fa-99b6-4ddc-ab26-b1cbe46fd96c',
                'user2' => '2d6b0a11-196f-47fa-bce9-ed92fb685c55',
            ],
            [
                'user1' => '2d6b0a11-196f-47fa-bce9-ed92fb685c55',
                'user2' => '427db419-d3f1-4974-b2b7-ea0acc95d25f',
            ]
        ];

        foreach ($userPairs as $pair) {
            $conversationId = Str::uuid()->toString();

            // Tạo conversation
            DB::table('conversations')->insert([
                'id' => $conversationId,
                'type' => 'private',
                'name' => null,
                'created_by' => $pair['user1'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Thêm 2 user vào bảng conversation_user
            DB::table('conversation_user')->insert([
                [
                    'id' => Str::uuid()->toString(),
                    'conversation_id' => $conversationId,
                    'user_id' => $pair['user1'],
                    'joined_at' => $now,
                    'role' => 'member',
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'conversation_id' => $conversationId,
                    'user_id' => $pair['user2'],
                    'joined_at' => $now,
                    'role' => 'member',
                ],
            ]);
        }
    }
}
