<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;

use Exception;
use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\DataType\Date;
use IOL\Generic\v1\DataType\UUID;
use IOL\Generic\v1\Exceptions\InvalidValueException;
use IOL\Generic\v1\Exceptions\NotFoundException;

class Ticket
{
    public const DB_TABLE = 'tickets';

    private string $id;
    private Order $order;
    private User $user;
    private ?Date $created;

    /**
     * @throws InvalidValueException
     * @throws NotFoundException
     */
    public function __construct(?string $id = null)
    {
        if (!is_null($id)) {
            if (!UUID::isValid($id)) {
                throw new InvalidValueException('Invalid Ticket ID');
            }
            $this->loadData(Database::getRow('id', $id, self::DB_TABLE));
        }
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    private function loadData(array|false $values): void
    {

        if (!$values || count($values) === 0) {
            throw new NotFoundException('Ticket could not be loaded');
        }

        $this->id = $values['id'];
        $this->user = new User($values['user_id']);
        $this->order = new Order($values['order_id']);
        $this->created = new Date($values['created']);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

}
