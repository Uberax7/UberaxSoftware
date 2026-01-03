<?php

namespace Shopier\Models;

class Address extends BaseModel
{
    protected static $requirement = [
        'address' => true,
        'city' => true,
        'country' => false,
        'postcode' => false,
    ];

    public $address;
    public $city;
    public $country;
    public $postcode;

    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }
}