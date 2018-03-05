<?php

namespace RedmineBundle\Pagination\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use Redmine\Client;
use RedmineBundle\Connection;
use RedmineBundle\Dto\ProjectDto;

class ProjectApiAdapter implements AdapterInterface
{
    /** @var Client */
    private $client;

    /** @var integer */
    private $totalCount;

    public function __construct(Connection $connection)
    {
        $this->client = $connection->getClient();
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        if (null === $this->totalCount) {
            $response = $this->client->project->all(['limit' => 1]);

            return $this->totalCount = $response['total_count'];
        }

        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $limit)
    {
        $response = $this->client->project->all([
            'offset' => $offset,
            'limit' => $limit
        ]);

        $this->totalCount = $response['total_count'];

        $result = [];
        if (array_key_exists('projects', $response) && is_array($response['projects'])) {
            foreach ($response['projects'] as $project) {
                $result[] = new ProjectDto($project);
            }
        }

        return $result;
    }
}
