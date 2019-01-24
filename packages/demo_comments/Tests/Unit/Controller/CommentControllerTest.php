<?php
namespace OliverHader\DemoComments\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class CommentControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \OliverHader\DemoComments\Controller\CommentController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\OliverHader\DemoComments\Controller\CommentController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllCommentsFromRepositoryAndAssignsThemToView()
    {

        $allComments = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $commentRepository = $this->getMockBuilder(\OliverHader\DemoComments\Domain\Repository\CommentRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $commentRepository->expects(self::once())->method('findAll')->will(self::returnValue($allComments));
        $this->inject($this->subject, 'commentRepository', $commentRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('comments', $allComments);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenCommentToView()
    {
        $comment = new \OliverHader\DemoComments\Domain\Model\Comment();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('comment', $comment);

        $this->subject->showAction($comment);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenCommentToCommentRepository()
    {
        $comment = new \OliverHader\DemoComments\Domain\Model\Comment();

        $commentRepository = $this->getMockBuilder(\OliverHader\DemoComments\Domain\Repository\CommentRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $commentRepository->expects(self::once())->method('add')->with($comment);
        $this->inject($this->subject, 'commentRepository', $commentRepository);

        $this->subject->createAction($comment);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenCommentToView()
    {
        $comment = new \OliverHader\DemoComments\Domain\Model\Comment();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('comment', $comment);

        $this->subject->editAction($comment);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenCommentInCommentRepository()
    {
        $comment = new \OliverHader\DemoComments\Domain\Model\Comment();

        $commentRepository = $this->getMockBuilder(\OliverHader\DemoComments\Domain\Repository\CommentRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $commentRepository->expects(self::once())->method('update')->with($comment);
        $this->inject($this->subject, 'commentRepository', $commentRepository);

        $this->subject->updateAction($comment);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenCommentFromCommentRepository()
    {
        $comment = new \OliverHader\DemoComments\Domain\Model\Comment();

        $commentRepository = $this->getMockBuilder(\OliverHader\DemoComments\Domain\Repository\CommentRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $commentRepository->expects(self::once())->method('remove')->with($comment);
        $this->inject($this->subject, 'commentRepository', $commentRepository);

        $this->subject->deleteAction($comment);
    }
}
