<?php
    namespace LaravelCommode\BladedEditors;

    use LaravelCommode\Bladed\Interfaces\IBladedManager;
    use LaravelCommode\Bladed\Manager\BladedManager;
    use LaravelCommode\BladedEditors\Interfaces\IManager;
    use PHPUnit_Framework_MockObject_MockObject as Mock;

    use Illuminate\Foundation\Application;

    class BladedEditorsServiceProviderTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var BladedEditorsServiceProvider
         */
        private $service;

        /**
         * @var Application|Mock
         */
        private $applicationMock;

        /**
         * @var Manager
         */
        private $manager;

        /**
         * @var IBladedManager|Mock
         */
        private $bladedManager;

        protected function setUp()
        {
            $this->bladedManager = $this->getMock('LaravelCommode\Bladed\Manager\BladedManager', [], [], '', false);
            $this->applicationMock = $this->getMock('Illuminate\Foundation\Application', [
                'singleton', 'bindShared', 'make'
            ], []);
            $this->service = new BladedEditorsServiceProvider($this->applicationMock);
            $this->manager = new Manager();
        }

        public function testRegistering()
        {
            $this->applicationMock->expects($this->any())->method('make')->with('commode.bladed')->will($this->returnValue($this->bladedManager));

            $reflection = new \ReflectionMethod($this->service, 'registering');
            $reflection->setAccessible(true);
            $reflection->invoke($this->service);

            $reflection = new \ReflectionMethod($this->service, 'launching');
            $reflection->setAccessible(true);
            $reflection->invoke($this->service);
        }

        public function testUses()
        {
            $reflection = new \ReflectionMethod($this->service, 'uses');
            $reflection->setAccessible(true);
            $this->assertSame($reflection->invoke($this->service), ['LaravelCommode\Bladed\BladedServiceProvider']);
        }

        public function testProvides()
        {
            $this->assertSame($this->service->provides(), [
                'LaravelCommode\BladedEditors\Interfaces\IManager',
                'commode.bladed.editorsManager'
            ]);
        }

        protected function tearDown()
        {
            unset($this->applicationMock);
            unset($this->service);
        }
    }
