<?php
/**
 * Created by PhpStorm.
 * User: madman
 * Date: 23.06.15
 * Time: 14:17
 */

namespace LaravelCommode\BladedEditors;

use LaravelCommode\Bladed\BladedServiceProvider;
use LaravelCommode\Bladed\Interfaces\IBladedManager;
use LaravelCommode\Bladed\Manager\BladedManager;
use LaravelCommode\BladedEditors\Commands\Editor;
use LaravelCommode\BladedEditors\Interfaces\IManager;
use LaravelCommode\Utils\Tests\PHPUnitContainer;

use PHPUnit_Framework_MockObject_MockObject as Mock;

class BladedEditorsServiceProviderTest extends PHPUnitContainer
{
    /**
     * @var BladedEditorsServiceProvider
     */
    private $testInstance;

    /**
     * @var Manager|IManager|Mock
     */
    private $managerMock;

    /**
     * @var IBladedManager|BladedManager|Mock
     */
    private $bladedManagerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->testInstance = new BladedEditorsServiceProvider($this->getApplicationMock());
        $this->managerMock = $this->getMock(Manager::class);
        $this->bladedManagerMock = $this->getMock(BladedManager::class, [], [], '', false);
    }

    public function testUses()
    {
        $expect = [BladedServiceProvider::class];

        $usesReflection = new \ReflectionMethod($this->testInstance, 'uses');
        $usesReflection->setAccessible(true);
        $this->assertSame($expect, $usesReflection->invoke($this->testInstance));
        $usesReflection->setAccessible(false);
    }

    public function testRegister()
    {
        $this->getApplicationMock()->expects($this->exactly(1))->method('singleton')
            ->with(IManager::class, Manager::class);

        $this->getApplicationMock()->expects($this->exactly(1))->method('bind')
            ->will($this->returnCallback(function ($bound, $boundTo) {

                $this->assertTrue($boundTo($this->getApplicationMock()) instanceof IManager);

                return null;
            }));

        $this->getApplicationMock()->expects($this->any())->method('make')
            ->will($this->returnCallback(function ($make) {
                switch ($make)
                {
                    case BladedServiceProvider::PROVIDES_SERVICE:
                        return $this->bladedManagerMock;
                    case IManager::class:
                        return $this->managerMock;
                }
            }));

        $this->bladedManagerMock->expects($this->once())->method('registerCommandNamespace')
            ->with('editor', Editor::class);

        $this->testInstance->registering();
    }

    public function testLaunching()
    {
        $this->testInstance->launching();
    }

    public function testProvides()
    {
        $this->assertSame(
            [IManager::class, BladedEditorsServiceProvider::PROVIDES_SERVICE],
            $this->testInstance->provides()
        );
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
