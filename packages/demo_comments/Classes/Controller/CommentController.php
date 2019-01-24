<?php
namespace OliverHader\DemoComments\Controller;


use OliverHader\DemoComments\Domain\Repository\CommentRepository;

/***
 *
 * This file is part of the "Demo Comments" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Oliver Hader <oliver.hader@typo3.org>
 *
 ***/
/**
 * CommentController
 */
class CommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var CommentRepository
     */
    protected $commentRepository = null;

    /**
     * @param CommentRepository $commentRepository
     */
    public function injectCommentRepository(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $comments = $this->commentRepository->findAll();
        $this->view->assign('comments', $comments);
    }

    /**
     * @param \OliverHader\DemoComments\Domain\Model\Comment $newComment
     */
    public function createAction(\OliverHader\DemoComments\Domain\Model\Comment $newComment)
    {
        $newComment->setDate(new \DateTime('now'));
        $this->commentRepository->add($newComment);
        $this->redirect('list');
    }
}
