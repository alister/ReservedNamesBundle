<?php
namespace Ca\ProfileBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Alister\ReservedNamesBundle\Services\ReservedNames;
use Alister\ReservedNamesBundle\Services\CleanUserNames;

class ReservedNamesServiceTest extends KernelTestCase
{
    /** @var Alister\ReservedNamesBundle\Services\ReservedNames */
    protected $rn;

    private static $container;

    public static function setUpBeforeClass()
    {
        //start the symfony kernel
        $kernel = static::createKernel();
        $kernel->boot();

        //get the DI container
        self::$container = $kernel->getContainer();
    }

    /* for reference, this is a copy of the configured 'local' reserved names
       from ./tests/app/config/config.yml
        * 'alister'
        * 'private'
        * 'website'
        * self::SECRET_NAME
    */

    // a 'secret name' that we also add, and then check for. It should fail
    // as we've added it as an extra to the list
    const SECRET_NAME = '5NF4ZG7AYVJ8TQDXPUHKFPYG';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * We list some of our 'local' reservations, to test that part works
     */
    protected function setUp()
    {
        // now we can instantiate our service (if you want a fresh one for
        // each test method, do this in setUp() instead
        $this->rn = self::$container->get('alister_reserved_names.check');
    }

    /**
     * Test the setup of the service
     *
     * It should also have the local configuration for name reservation
     *
     * @return [type] [description]
     */
    public function testSanityService()
    {
        $this->assertInstanceOf('Alister\ReservedNamesBundle\Services\ReservedNames', $this->rn);

        $names = $this->rn->getReservedNames();
        $this->assertInternalType('array', $names);

        // we gave it an all-uppercase 'secret name', which it str-to-lower's'
        $this->assertArrayNotHasKey(self::SECRET_NAME, $names);
        $this->assertArrayHasKey(strtolower(self::SECRET_NAME), $names);

        $this->assertGreaterThanOrEqual(800, count($names));
        $this->assertArrayHasKey('alister', $names);
        $this->assertArrayHasKey('private', $names);
        $this->assertArrayHasKey('website', $names);
    }

    /**
     * Test against a list that should all be reserved
     *
     * @dataProvider reservedNamesDataProvider
     * @param string $value [description]
     *
     * @return void
     */
    public function testReservedNames($username, $reason)
    {
        $this->assertTrue($this->rn->isReserved($username), $reason);
    }

    /**
     * Test against a list that have no reserved names
     *
     * @param string $value [description]
     * @dataProvider notReservedNamesDataProvider
     *
     * @return void
     */
    public function testNotReservedNames($username, $reason)
    {
        $this->assertFalse($this->rn->isReserved($username), $reason);
    }

    public function reservedNamesDataProvider()
    {
        return array(
            [ 'alister',        'alister should be reserved' ],
            [ 'website',        'website should be reserved' ],
            [ 'private',        'private should be reserved' ],
            [ 'alister123',     'alister123 should be reserved' ],
            [ 'alister123s',    'alister123 should be reserved' ],
            [ 'alisters123',    'alister123 should be reserved' ],
            [ 'alister-_1-23 ', 'alister* should be reserved' ],
            [ 'postmasters',    'postmaster* should be reserved' ],
            [ 'www123s',        'www* should be reserved' ],
            [ 'contactus',      'contactus should also be reserved' ],
            [ 'contactu',       'contactu is in the reserved list' ],
        );
    }

    public function notReservedNamesDataProvider()
    {
        return array(
            [ 'contac',       'contac should not be reserved' ],
            [ 'wwwww',        '5 *w should not be reserved' ],
            [ 'notinthelist', 'notinthelist should not be reserved' ],

            // testCleanedNameIsDifferentButStillNotReserved
            [ 'notreserved',      'Cleaned name is diff, but still not reserved' ],
            [ 'notreserved123',   'Cleaned name is diff, but still not reserved' ],
            [ 'notreserveds',     'Cleaned name is diff, but still not reserved' ],
            [ 'notreserved-123s', 'Cleaned name is diff, but still not reserved' ],
        );
    }

    /**
     * Whatever we say, when it's prefixed with 'test' - it's a test.
     *
     * @return void
     */
    public function testReservedNameIsPrefixedWithtest()
    {
        $name = 'isthisreserved';  // not reserved, or a test - yet
        $this->assertFalse($this->rn->isTest($name));
        $this->assertFalse($this->rn->isReserved($name));

        $name = 'test' . $name;
        // now it's a test and so is also reserved
        $this->assertTrue($this->rn->isTest($name));
        $this->assertTrue($this->rn->isReserved($name));
    }

    /**
     * Finally, a plain check against isTest() alone
     *
     * @return void
     */
    public function testCheckingAgainstIsTest()
    {
        $this->assertTrue($this->rn->isTest('test'));
        $this->assertTrue($this->rn->isTest('test123123'));
        $this->assertTrue($this->rn->isTest('testali123ster'));
        $this->assertTrue($this->rn->isTest('test'));

        $this->assertFalse($this->rn->isTest('te123st'));   // not confused for a test

        $this->assertFalse($this->rn->isTest('www'));       // reserved, but not a test
        $this->assertFalse($this->rn->isTest('alister'));
        $this->assertFalse($this->rn->isTest('alister123'));
        $this->assertFalse($this->rn->isTest('alister123'));
        $this->assertFalse($this->rn->isTest('ali123ster'));
    }
}
