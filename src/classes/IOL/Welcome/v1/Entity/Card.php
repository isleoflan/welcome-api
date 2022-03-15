<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;


use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\Request\APIResponse;

class Card
{
    public const DB_TABLE = 'cards';

    private string $serial;
    private User $user;

    public function register(string $serial, User $user): void
    {
        if (!ctype_xdigit($serial)) {
            APIResponse::getInstance()->addError(880003)->render();
        }
        $database = Database::getInstance();
        $database->insert(self::DB_TABLE, [
            'serial' => strtoupper($serial),
            'user_id' => $user->getId()
        ]);
    }
}
