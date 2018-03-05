<?php

namespace RedmineBundle\Dto;

class ProjectDto
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $identifier;

    /** @var string */
    public $description;

    /** @var string */
    public $homepage;

    /** @var integer */
    public $status;

    /** @var CustomFieldDto[] */
    public $customFields = [];

    /** @var IdNameDto[] */
    public $trackers = [];

    /** @var string */
    public $createdOn;

    /** @var string */
    public $updatedOn;

    public function __construct($data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = (int)$data['id'];
        }

        if (array_key_exists('name', $data)) {
            $this->name = $data['name'];
        }

        if (array_key_exists('identifier', $data)) {
            $this->identifier = $data['identifier'];
        }

        if (array_key_exists('description', $data)) {
            $this->description = $data['description'];
        }

        if (array_key_exists('homepage', $data)) {
            $this->homepage = $data['homepage'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = $data['status'];
        }

        if (array_key_exists('custom_fields', $data) && is_array($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $custom_field) {
                $this->customFields[] = new CustomFieldDto($custom_field);
            }
        }

        if (array_key_exists('trackers', $data) && is_array($data['trackers'])) {
            foreach ($data['trackers'] as $tracker) {
                $this->trackers[] = new IdNameDto($tracker);
            }
        }

        if (array_key_exists('created_on', $data)) {
            $this->createdOn = $data['created_on'];
        }

        if (array_key_exists('updated_on', $data)) {
            $this->updatedOn = $data['updated_on'];
        }
    }
}
