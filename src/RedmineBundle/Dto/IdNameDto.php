<?php

namespace RedmineBundle\Dto;

class IdNameDto
{
    public $id;

    public $name;

    public function __construct($data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }

        if (array_key_exists('name', $data)) {
            $this->name = $data['name'];
        }
    }
}
