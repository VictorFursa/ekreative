<?php

namespace RedmineBundle;

use RedmineBundle\Dto\IssueDto;
use RedmineBundle\Dto\ProjectDto;

class ApiHelper
{
    /** @var \Redmine\Client */
    private $client;

    public function __construct(Connection $connection)
    {
        $this->client = $connection->getClient();
    }

    /**
     * @param integer $id
     * @return ProjectDto
     */
    public function getProjectById(int $id)
    {
        $response = $this->client->project->show($id);

        if (!$response || !array_key_exists('project', $response)) {
            return null;
        }

        return new ProjectDto($response['project']);
    }

    /**
     * @param integer $id
     * @return IssueDto
     */
    public function getIssueById(int $id)
    {
        $response = $this->client->issue->show($id);

        if (!$response || !array_key_exists('issue', $response)) {
            return null;
        }

        return new IssueDto($response['issue']);
    }
}
