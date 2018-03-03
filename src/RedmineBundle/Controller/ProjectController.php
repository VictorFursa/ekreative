<?php

namespace RedmineBundle\Controller;

use Pagerfanta\Pagerfanta;
use RedmineBundle\Pagination\Adapter\IssueApiAdapter;
use RedmineBundle\Pagination\Adapter\ProjectApiAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project_index")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $adapter = new ProjectApiAdapter($this->get('redmine.connection'));
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Project/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/project/{id}", name="view_project", requirements={"id"="\d+"})
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(int $id)
    {
        $client = $this->get('redmine.connection')->getClient();
        $project = $client->project->show($id);

        return $this->render('@Redmine/Project/view.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/project-issues/{id}", name="project_issues", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issuesAction(Request $request, int $id)
    {
        $page = $request->query->get('page', 1);
        $client = $this->get('redmine.connection')->getClient();
        $project = $client->project->show($id);

        $adapter = new IssueApiAdapter($client, $id);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Project/issues.html.twig', [
            'pager' => $pager,
            'project' => $project
        ]);
    }
}
