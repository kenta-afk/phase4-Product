<?php
namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;
    case USER = 2;
    case GUEST = 3;

    public function label(): string
    {
        return match ($this) {
            Role::ADMIN  => '管理者',
            Role::USER => 'ユーザー',
            Role::GUEST => 'ゲスト',
        };
    }
}
