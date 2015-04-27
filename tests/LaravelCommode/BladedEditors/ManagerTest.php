<?php
    namespace LaravelCommode\BladedEditors;


    use Illuminate\Filesystem\Filesystem;

    class ManagerTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var Manager
         */
        private $manager;

        protected function setUp()
        {
            $this->manager = new Manager();
        }

        public function testRegisterAndGrab()
        {
            $namespace = uniqid();

            $this->manager->addBinding(__NAMESPACE__, $namespace);

            $this->assertSame(
                $this->manager->guessEditor($this), $namespace."::".(new \ReflectionClass($this))->getShortName()
            );

            $this->assertSame($this->manager->guessEditor($fileSystem = new Filesystem()), "??::".(new \ReflectionClass($fileSystem))->getShortName());
        }

        protected function tearDown()
        {
            unset($this->manager);
        }
    }
