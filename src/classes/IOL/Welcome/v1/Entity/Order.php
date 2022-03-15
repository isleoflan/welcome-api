<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;

use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\DataType\Date;
use IOL\Generic\v1\DataType\UUID;
use IOL\Generic\v1\Exceptions\InvalidValueException;
use IOL\Generic\v1\Exceptions\NotFoundException;
use JetBrains\PhpStorm\Pure;

class Order
{
    public const DB_TABLE = 'orders';

    private string $id;
    private User $user;
    private Date $created;
    private string $paymentMethod;
    private string $voucher;
    private string $status;

    private array $items = [];

    public function __construct(?string $id = null)
    {
        if (!is_null($id)) {
            if (!UUID::isValid($id)) {
                throw new InvalidValueException('Invalid Order ID');
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
        $this->user = new User($values['user_id']);
        $this->created = $values['created'];
        $this->paymentMethod = $values['paymentMethod'];
        $this->voucher = $values['voucher'];
        $this->status = $values['status'];

        $database = Database::getInstance();
        $database->where('order_id', $this->id);
        foreach($database->get(self::DB_TABLE) as $item){
            $orderItem = new OrderItem();
            $orderItem->loadData($item);
            $this->addItem($orderItem);
        }
    }

    private function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }

    #[Pure] public function getMerch(): ?array
    {
        $return = [];
        foreach($this->items as $item) {
            /** @var OrderItem $item */
            if($item->getProduct()->getCategoryId() === 4) {
                $return[] = $item->getAmount() . 'x ' . $item->getProduct()->getTitle();
            }
        }
        return $return;
    }
}
