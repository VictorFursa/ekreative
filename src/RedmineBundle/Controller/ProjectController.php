<?php

namespace RedmineBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use RedmineBundle\Entity\Comment;
use RedmineBundle\Entity\Tracker;
use RedmineBundle\Form\CommentType;
use RedmineBundle\Form\TrackerType;
use RedmineBundle\Pagination\Adapter\ProjectIssueApiAdapter;
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

        if (false === $project) {
            return $this->render('@Redmine/project-not-found.html.twig');
        }

        return $this->render('@Redmine/Project/view.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/project/{id}/issue/list", name="project_issues", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issueListAction(Request $request, int $id)
    {
        $client = $this->get('redmine.connection')->getClient();
        $project = $client->project->show($id);

        if (false === $project) {
            return $this->render('@Redmine/project-not-found.html.twig');
        }

        $page = $request->query->get('page', 1);
        $adapter = new ProjectIssueApiAdapter($client, $id);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Project/issues.html.twig', [
            'pager' => $pager,
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{id}/comments/list", name="project_comments")
     *
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function commentsListAction(Request $request, int $id)
    {
        $project = $this->get('redmine.connection')->getClient()->project->show($id);

        if (false === $project) {
            return $this->render('@Redmine/project-not-found.html.twig');
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->getCommentsByProject($id);
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
     * @Route("/project/{id}/comment/add", name="add_comment", requirements={"projectId"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addCommentAction(Request $request, int $id)
    {
        $comment = new Comment();
        $comment->setProjectId($id);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('project_comments', ['id' => $id]);
        }

        return $this->render('@Redmine/Project/add-comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/project/{id}/time-track", name="project_time_track")
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function timeTrackAction(Request $request, int $id)
    {
        $tracker = new Tracker();
        $form = $this->createForm(TrackerType::class, $tracker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->get('redmine.connection')->getClient();
            $client->time_entry->create([
                'project_id' => $id,
                'hours' => $tracker->getTime(),
            ]);

            return $this->redirectToRoute('project_index');
        }

        return $this->render('@Redmine/Project/track-time.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
