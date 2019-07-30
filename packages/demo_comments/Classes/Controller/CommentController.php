<?php
namespace OliverHader\DemoComments\Controller;

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

use OliverHader\DemoComments\Domain\Model\Comment;
use OliverHader\DemoComments\Domain\Repository\CommentRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * CommentController
 */
class CommentController extends ActionController
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
     * @param Comment $newComment
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function createAction(Comment $newComment)
    {
        $newComment->setDate(new \DateTime('now'));
        $this->commentRepository->add($newComment);
        $this->redirect('list');
    }
}
