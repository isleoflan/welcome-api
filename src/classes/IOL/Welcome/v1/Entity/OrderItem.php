<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;

use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\DataType\UUID;
use IOL\Generic\v1\Exceptions\InvalidValueException;
use IOL\Generic\v1\Exceptions\NotFoundException;

class OrderItem
{
    public const DB_TABLE = 'order_items';

    private string $id;
    private Product $product;
    private int $amount;

    public function __construct(?string $id = null)
    {
        if (!is_null($id)) {
            if (!UUID::isValid($id)) {
                throw new InvalidValueException('Invalid Order Item ID');
            }
            $this->loadData(Database::getRow('id', $id, self::DB_TABLE));
        }
    }

    /**
     * @throws NotFoundException
     */
    public function loadData(array|false $values): void
    {
        if (!$values || count($values) === 0) {
            throw new NotFoundException('User could not be loaded');
        }

        $this->id = $values['id'];
        $this->product = new Product($values['product_id']);
        $this->amount = $values['amount'];
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

}
