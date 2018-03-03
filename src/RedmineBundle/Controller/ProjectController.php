<?php

namespace RedmineBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use RedmineBundle\Entity\Comment;
use RedmineBundle\Form\CommentType;
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
        $client = $this->get('redmine.connection')->getClient();
        $project = $client->project->show($id);

        if (false === $project) {
            return $this->render('@Redmine/error404.html.twig');
        }

        $page = $request->query->get('page', 1);
        $adapter = new IssueApiAdapter($client, $id);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Project/issues.html.twig', [
            'pager' => $pager,
            'project' => $project
        ]);
    }

    /**
     * @Route("/comments/project/{projectId}", name="project_comments")
     *
     * @param int $projectId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function commentsListAction(Request $request, int $projectId)
    {
        $project = $this->get('redmine.connection')->getClient()->project->show($projectId);
        if (false === $project) {
            return $this->render('@Redmine/error404.html.twig');
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->getCommentsByProject($projectId);
        $adapter = new DoctrineORMAdapter($comments);
        $pager = new Pagerfanta($adapter);
        $page = $request->query->get('page', 1);
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Project/comments.html.twig', [
            'project' => $project,
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/add-comment/project/{projectId}", name="add_comment", requirements={"projectId"="\d+"})
     * @param Request $request
     * @param int $projectId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addCommentAction(Request $request, int $projectId)
    {
        $comment = new Comment();
        $comment->setProjectId($projectId);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('project_comments', ['projectId' => $comment->getProjectId()]);
        }

        return $this->render('@Redmine/Project/add-comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
