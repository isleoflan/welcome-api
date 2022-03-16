<?php

declare(strict_types=1);

namespace IOL\Welcome\v1\Entity;

use IOL\Generic\v1\DataSource\Database;
use IOL\Generic\v1\DataType\Date;
use IOL\Generic\v1\DataType\UUID;
use IOL\Generic\v1\Enums\Gender;
use IOL\Generic\v1\Exceptions\InvalidValueException;
use IOL\Generic\v1\Exceptions\NotFoundException;
use JetBrains\PhpStorm\ArrayShape;

class User
{
    public const DB_TABLE = 'user';

    private string $id;
    private string $username;

    private Gender $gender;
    private string $foreName;
    private string $lastName;
    private string $address;
    private int $zipCode;
    private string $city;
    private Date $birthDate;

    public function __construct(?string $id = null)
    {
        if (!is_null($id)) {
            if (!UUID::isValid($id)) {
                throw new InvalidValueException('Invalid Login Request ID');
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
        $this->username = $values['username'];
        $this->gender = new Gender($values['gender']);
        $this->foreName = $values['forename'];
        $this->lastName = $values['lastname'];
        $this->address = $values['address'];
        $this->zipCode = (int)$values['zip_code'];
        $this->city = $values['city'];
        $this->birthDate = new Date($values['birth_date']);
    }

    #[ArrayShape([
        'username' => "string",
        'gender' => "string",
        'forename' => "string",
        'lastname' => "string",
        'address' => "string",
        'zipCode' => "int",
        'city' => "string",
        'birthDate' => "string"
    ])]
    public function serialize(): array
    {
        return [
            'username' => $this->username,
            'gender' => $this->gender->getValue(),
            'forename' => $this->foreName,
            'lastname' => $this->lastName,
            'address' => $this->address,
            'zipCode' => $this->zipCode,
            'city' => $this->city,
            'birthDate' => $this->birthDate->iso()
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
