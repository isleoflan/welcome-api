<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;

use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\DataType\UUID;
use IOL\Generic\v1\Exceptions\InvalidValueException;
use IOL\Generic\v1\Exceptions\NotFoundException;

class Product
{
    public const DB_TABLE = 'order_items';

    private string $id;
    private int $categoryId;
    private string $number;
    private string $title;

    public function __construct(?string $id = null)
    {
        if (!is_null($id)) {
            if (!UUID::isValid($id)) {
                throw new InvalidValueException('Invalid Product ID');
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
        $this->categoryId = $values['category_id'];
        $this->number = $values['product_number'];
        $this->title = $values['title'];
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

}
