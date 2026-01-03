<?php

namespace Shopier\Models;

class Buyer extends BaseModel
{
    protected static $requirement = [
        'id' => true,
        'name' => true,
        'surname' => true,
        'email' => true,
        'phone' => true,
        'account_age' => false,
    ];

    public $id;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $account_age;

    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }
}