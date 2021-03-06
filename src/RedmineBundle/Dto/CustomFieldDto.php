<?php

namespace RedmineBundle\Dto;

class CustomFieldDto
{
    public $id;

    public $name;

    public $value;

    public function __construct($data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }

        if (array_key_exists('name', $data)) {
            $this->name = $data['name'];
        }

        if (array_key_exists('value', $data)) {
            $this->value = $data['value'];
        }
    }
}
