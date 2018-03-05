<?php

namespace RedmineBundle\Dto;

class IssueDto
{
    /** @var integer */
    public $id;

    /** @var integer */
    public $parentId;

    /** @var string */
    public $description;

    /** @var string */
    public $subject;

    /** @var IdNameDto */
    public $project;

    /** @var IdNameDto */
    public $tracker;

    /** @var IdNameDto */
    public $status;

    /** @var IdNameDto */
    public $priority;

    /** @var IdNameDto */
    public $author;

    /** @var string */
    public $startDate;

    /** @var integer */
    public $doneRatio;

    /** @var integer */
    public $spentHours;

    /** @var integer */
    public $totalSpentHours;

    /** @var CustomFieldDto[] */
    public $customFields = [];

    /** @var string */
    public $createdOn;

    /** @var string */
    public $updatedOn;

    public function __construct($data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = (int)$data['id'];
        }

        if (array_key_exists('parent', $data) &&
            is_array($data['parent']) &&
            array_key_exists('id', $data['parent'])
        ) {
            $this->parentId = (int)$data['parent']['id'];
        }

        if (array_key_exists('description', $data)) {
            $this->description = $data['description'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = $data['status'];
        }

        if (array_key_exists('custom_fields', $data) && is_array($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $custom_field) {
                $this->customFields[] = new CustomFieldDto($custom_field);
            }
        }

        if (array_key_exists('project', $data) && is_array($data['project'])) {
            $this->project = new IdNameDto($data['project']);
        }

        if (array_key_exists('tracker', $data) && is_array($data['tracker'])) {
            $this->tracker = new IdNameDto($data['tracker']);
        }

        if (array_key_exists('status', $data) && is_array($data['status'])) {
            $this->status = new IdNameDto($data['status']);
        }

        if (array_key_exists('priority', $data) && is_array($data['priority'])) {
            $this->priority = new IdNameDto($data['priority']);
        }

        if (array_key_exists('author', $data) && is_array($data['author'])) {
            $this->author = new IdNameDto($data['author']);
        }

        if (array_key_exists('subject', $data)) {
            $this->subject = $data['subject'];
        }

        if (array_key_exists('start_date', $data)) {
            $this->startDate = $data['start_date'];
        }

        if (array_key_exists('done_ratio', $data)) {
            $this->doneRatio = $data['done_ratio'];
        }

        if (array_key_exists('spent_hours', $data)) {
            $this->spentHours = $data['spent_hours'];
        }

        if (array_key_exists('total_spent_hours', $data)) {
            $this->totalSpentHours = $data['total_spent_hours'];
        }

        if (array_key_exists('created_on', $data)) {
            $this->createdOn = $data['created_on'];
        }

        if (array_key_exists('updated_on', $data)) {
            $this->updatedOn = $data['updated_on'];
        }
    }
}
