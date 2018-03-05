<?php

namespace RedmineBundle\Pagination\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use Redmine\Client;
use RedmineBundle\Dto\IssueDto;

class ProjectIssueApiAdapter implements AdapterInterface
{
    /** @var Client */
    private $client;

    /** @var integer|null */
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

        if (isset($response[0]) && false === $response[0]) {
            return null;
        }

        $this->totalCount = $response['total_count'];

        $result = [];
        if (array_key_exists('issues', $response) && is_array($response['issues'])) {
            foreach ($response['issues'] as $issue) {
                $result[] = new IssueDto($issue);
            }
        }

        return $result;
    }


    /**
     * @return int|null
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}
