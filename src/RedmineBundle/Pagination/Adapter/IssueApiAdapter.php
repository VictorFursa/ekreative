<?php

namespace RedmineBundle\Pagination\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use Redmine\Client;

class IssueApiAdapter implements AdapterInterface
{
    /** @var Client */
    private $client;

    /** @var integer */
    private $totalCount;

    /** @var integer */
    private $projectId;

    public function __construct(Client $client, int $projectId)
    {
        $this->client = $client;
        $this->projectId = $projectId;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        if (null === $this->totalCount) {
            $response = $this->client->issue->all([
                'limit' => 1,
                'project_id' => $this->projectId
            ]);

           return $this->totalCount = $response['total_count'];
        }

        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $limit)
    {
        $response = $this->client->issue->all([
            'project_id' => $this->projectId,
            'offset' => $offset,
            'limit' => $limit
        ]);

        $this->totalCount = $response['total_count'];

        return $response['issues'];
    }
}
