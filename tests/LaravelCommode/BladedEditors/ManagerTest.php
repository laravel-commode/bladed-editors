<?php

namespace LaravelCommode\BladedEditors;

use Illuminate\Filesystem\Filesystem;
use LaravelCommode\BladedEditors\Commands\Editor;
use LaravelCommode\BladedEditors\Interfaces\IManager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager|IManager
     */
    private $testInstance;

    protected function setUp()
    {
        parent::setUp();

        $this->testInstance = new Manager();
    }

    public function testAll()
    {
        $this->testInstance->addEditorBinding(__NAMESPACE__, 'global');
        $this->testInstance->addEditorBinding('Illuminate\Filesystem', 'file');

        $this->testInstance->addDisplayBinding(__NAMESPACE__, 'dglobal');
        $this->testInstance->addDisplayBinding('Illuminate\Filesystem', 'dfile');

        $this->assertSame('global::ManagerTest', $this->testInstance->guessEditor(static::class));
        $this->assertSame('file::Filesystem', $this->testInstance->guessEditor($instance = new Filesystem()));

        $this->assertSame('dglobal::ManagerTest', $this->testInstance->guessDisplay(static::class));
        $this->assertSame('dfile::Filesystem', $this->testInstance->guessDisplay($instance));

        $expectFail = ['guessDisplay', 'guessEditor'];

        foreach ($expectFail as $methodName) {
            try {
                call_user_func_array([$this->testInstance, $methodName], [Editor::class]);
            } catch (\Exception $e) {
                $this->assertTrue($e instanceof \InvalidArgumentException);
            }
        }
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
