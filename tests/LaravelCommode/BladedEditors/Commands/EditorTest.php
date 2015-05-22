<?php

namespace LaravelCommode\BladedEditors\Commands;

use Illuminate\View\Factory;
use LaravelCommode\BladedEditors\Manager;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class EditorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Editor
     */
    private $editor;

    /**
     * @var Factory|Mock
     */
    private $view;

    /**
     * @var \Illuminate\Foundation\Application|Mock
     */
    private $applicationMock;

    /**
     * @var Manager
     */
    private $manager;

    protected function setUp()
    {
        $this->applicationMock = $this->getMock('Illuminate\Foundation\Application', ['make']);

        $this->manager = new Manager();

        $this->applicationMock->expects($this->any())->method('make')
            ->with('LaravelCommode\BladedEditors\Interfaces\IManager')
            ->will($this->returnValue($this->manager));

        $this->view = $this->getMock('Illuminate\View\Factory', [], [], '', false);
        $this->editor = new Editor($this->applicationMock);
        $this->editor->setEnvironment($this->view);
    }

    public function testModel()
    {
        $this->view->expects($this->any())->method('getShared')->will($this->returnValue([]));
        $this->view->expects($this->once())->method('make');

        $this->editor->model($this);
    }
}
