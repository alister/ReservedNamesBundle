<?php
namespace Ca\ProfileBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Alister\ReservedNamesBundle\Services\ReservedNames;
use Alister\ReservedNamesBundle\Services\CleanUserNames;

class ReservedNamesServiceTest extends KernelTestCase
{
    /** @var Alister\ReservedNamesBundle\Services\ReservedNames */
    protected $rn;

    static private $container;

    public static function setUpBeforeClass()
    {
         //start the symfony kernel
         $kernel = static::createKernel();
         $kernel->boot();

         //get the DI container
         self::$container = $kernel->getContainer();
    }

    const SECRET_NAME = '5NF4ZG7AYVJ8TQDXPUHKFPYG';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // this is a copy of the configured 'local' reserved names 
        // from ./tests/app/config/config.yml
        $this->names = array(
            'alister', 
            'private',
            'website',
            self::SECRET_NAME,
        );

        //now we can instantiate our service (if you want a fresh one for
        //each test method, do this in setUp() instead
        $this->rn = self::$container->get('alister_reserved_names.check');
    }

    public function testSanityClass()
    {
        $this->assertInstanceOf('Alister\ReservedNamesBundle\Services\ReservedNames', $this->rn);

        $names = $this->rn->getReservedNames();
        $this->assertInternalType('array', $names);
        $this->assertArrayHasKey(strtolower(self::SECRET_NAME), $names);

        $this->assertGreaterThanOrEqual(800, count($names));
        $this->assertArrayHasKey('alister', $names);
    }

    public function testCheckingAgainstReservedName()
    {
        $this->assertTrue($this->rn->isReserved('alister'), 'alister should be reserved');
        $this->assertTrue($this->rn->isReserved('website'), 'website should be reserved');
        $this->assertTrue($this->rn->isReserved('private'), 'private should be reserved');
        $this->assertTrue($this->rn->isReserved('alister123'), 'alister123 should be reserved');

        $this->assertFalse($this->rn->isReserved('notinthelist'), 'notinthelist should not be reserved');
    }

    public function testCleanedNameIsDifferentButStillNotReserved()
    {
        $this->assertFalse($this->rn->isReserved('notreserved'));
        $this->assertFalse($this->rn->isReserved('notreserved123'));
    }

    public function testReservedNameIsPrefixedWithtest()
    {
        $name = 'isthisreserved';  // not reserved, or a test
        $this->assertFalse($this->rn->isReserved($name));
        $this->assertFalse($this->rn->isTest($name));

        $name = "test" . $name;
        // is a test and so is reserved
        $this->assertTrue($this->rn->isTest($name));
        $this->assertTrue($this->rn->isReserved($name));
    }

    public function testCheckingAgainstTest()
    {
        $this->assertTrue($this->rn->isTest('test'));
        $this->assertTrue($this->rn->isTest('test123123'));

        $this->assertFalse($this->rn->isTest('alister'));
        $this->assertFalse($this->rn->isTest('alister123'));
        $this->assertFalse($this->rn->isTest('alister123'));
        $this->assertFalse($this->rn->isTest('ali123ster'));

        $this->assertTrue($this->rn->isTest('testali123ster'));
    }
}
