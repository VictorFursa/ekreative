<?php

namespace RedmineBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use RedmineBundle\ApiHelper;
use RedmineBundle\Entity\Comment;
use RedmineBundle\Entity\Tracker;
use RedmineBundle\Form\CommentType;
use RedmineBundle\Form\TrackerType;
use RedmineBundle\Pagination\Adapter\ProjectIssueApiAdapter;
use RedmineBundle\Pagination\Adapter\ProjectApiAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class IssueController extends Controller
{

    /**
     * @Route("/issue/{id}", name="view_issue", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, int $id)
    {
        /** @var ApiHelper $apiHelper */
        $apiHelper = $this->get('redmine.api_helper');
        $issueDto = $apiHelper->getIssueById($id);

        if (!$issueDto) {
            return $this->render('@Redmine/page-not-found.html.twig', ['page' => 'Issue']);
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->getCommentsByProject($id);
        $adapter = new DoctrineORMAdapter($comments);
        $pager = new Pagerfanta($adapter);
        $page = $request->query->get('page', 1);
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($page);

        return $this->render('@Redmine/Issue/view.html.twig', [
            'issue' => $issueDto,
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/issue/{id}/time-track", name="issue_time_track")
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
                'issue_id' => $id,
                'hours' => $tracker->getTime(),
            ]);

            return $this->redirectToRoute('view_issue', ['id' => $id]);
        }

        return $this->render('@Redmine/track-time.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
