<?php

namespace LaravelCommode\BladedEditors\Commands;

use Illuminate\Contracts\View\Factory;

use LaravelCommode\BladedEditors\Interfaces\IManager;
use LaravelCommode\BladedEditors\Manager;
use LaravelCommode\Utils\Tests\PHPUnitContainer;

use PHPUnit_Framework_MockObject_MockObject as Mock;

class EditorTest extends PHPUnitContainer
{
    /**
     * @var Editor
     */
    private $testInstance;

    /**
     * @var Factory|Mock
     */
    private $viewFactoryMock;

    /**
     * @var Manager|IManager
     */
    private $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->testInstance = new Editor($this->getApplicationMock());

        $this->viewFactoryMock = $this->getMock(
            Factory::class,
            ['exists', 'file', 'make', 'share', 'composer', 'creator', 'addNamespace', 'getShared'],
            [],
            '',
            false
        );

        $this->manager = new Manager();

        $this->testInstance->setEnvironment($this->viewFactoryMock);
    }

    public function testDisplay()
    {
        $this->getApplicationMock()->expects($this->any())->method('make')
            ->will($this->returnCallback(function ($make) {
                switch ($make)
                {
                    case IManager::class:
                        return $this->manager;
                        break;
                }
            }));

        $this->viewFactoryMock->expects($this->exactly(1))->method('getShared')
            ->will($this->returnValue([]));

        $this->manager->addDisplayBinding(__NAMESPACE__, 'displays');

        $this->viewFactoryMock->expects($this->exactly(1))->method('make')
            ->with('displays::EditorTest')
            ->will($this->returnValue($displayValue = uniqid()));

        $this->assertSame($displayValue, $this->testInstance->display($this));
    }

    public function testEditor()
    {
        $this->getApplicationMock()->expects($this->any())->method('make')
            ->will($this->returnCallback(function ($make) {
                switch ($make)
                {
                    case IManager::class:
                        return $this->manager;
                        break;
                }
            }));

        $this->viewFactoryMock->expects($this->exactly(1))->method('getShared')
            ->will($this->returnValue([]));

        $this->manager->addEditorBinding(__NAMESPACE__, 'editors');

        $this->viewFactoryMock->expects($this->exactly(1))->method('make')
            ->with('editors::EditorTest')
            ->will($this->returnValue($editorValue = uniqid()));

        $this->assertSame($editorValue, $this->testInstance->editor($this));
    }

    protected function tearDown()
    {
        unset($this->viewFactoryMock, $this->testInstance);
        parent::tearDown();
    }
}
