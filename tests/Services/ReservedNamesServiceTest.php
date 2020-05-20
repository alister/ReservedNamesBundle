<?php
declare(strict_types=1);
namespace Ca\ProfileBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservedNamesServiceTest extends WebTestCase
{
    /** @var Alister\ReservedNamesBundle\Services\ReservedNames */
    protected $rn;

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
    protected function setUp(): void
    {
        self::bootKernel();

        // now we can instantiate our service
        $this->rn = self::$container->get('alister_reserved_names.check');
    }

    /**
     * Test the setup of the service.
     *
     * It should also have the local configuration for name reservation
     *
     * @return [type] [description]
     */
    public function testSanityService(): void
    {
        $this->assertInstanceOf('Alister\ReservedNamesBundle\Services\ReservedNames', $this->rn);

        $names = $this->rn->getReservedNames();
        $this->assertIsArray($names);

        // we gave it an all-uppercase 'secret name', which it str-to-lower's'
        $this->assertArrayNotHasKey(self::SECRET_NAME, $names);
        $this->assertArrayHasKey(strtolower(self::SECRET_NAME), $names);

        $this->assertGreaterThanOrEqual(800, count($names));
        $this->assertArrayHasKey('alister', $names);
        $this->assertArrayHasKey('private', $names);
        $this->assertArrayHasKey('website', $names);
    }

    /**
     * Test against a list that should all be reserved.
     *
     * @dataProvider reservedNamesDataProvider
     *
     * @param string $value [description]
     */
    public function testReservedNames($username, $reason): void
    {
        $this->assertTrue($this->rn->isReserved($username), $reason);
    }

    /**
     * Test against a list that have no reserved names.
     *
     * @param string $value [description]
     * @dataProvider notReservedNamesDataProvider
     */
    public function testNotReservedNames($username, $reason): void
    {
        $this->assertFalse($this->rn->isReserved($username), $reason);
    }

    public function reservedNamesDataProvider(): array
    {
        return [
            ['alister',        'alister should be reserved'],
            ['alister-_1-23 ', 'alister* should be reserved'],
            ['alister123',     'alister123 should be reserved'],
            ['alister123s',    'alister123 should be reserved'],
            ['alisters123',    'alister123 should be reserved'],
            ['contactu',       'contactu should be reserved'],
            ['contactus',      'contactus should be reserved'],
            ['crossdomain',    'crossdomain should be reserved'],
            ['favicon',        'favicon should be reserved'],
            ['htaccess ',      'htaccess should be reserved'],
            ['postmasters',    'postmaster* should be reserved'],
            ['private',        'private should be reserved'],
            ['robots',         'robots should be reserved'],
            ['website',        'website should be reserved'],
            ['well-known',     'well-known should be reserved'],
            ['www123s',        'www* should be reserved'],
            ['clientaccesspolicy', 'clientaccesspolicy.xml should be reserved'],
        ];
    }

    public function notReservedNamesDataProvider(): array
    {
        return [
            ['contac',       'contac should not be reserved'],
            ['wwwww',        '5 *w should not be reserved'],
            ['notinthelist', 'notinthelist should not be reserved'],
            //[ 'ca12345ab',    'ca12345ab should not be reserved' ], but collapses to 'ca', which is

            // testCleanedNameIsDifferentButStillNotReserved
            ['notreserved',      'Cleaned name is diff, but still not reserved'],
            ['notreserved123',   'Cleaned name is diff, but still not reserved'],
            ['notreserveds',     'Cleaned name is diff, but still not reserved'],
            ['notreserved-123s', 'Cleaned name is diff, but still not reserved'],
        ];
    }

    /**
     * Whatever we say, when it's prefixed with 'test' - it's a test.
     */
    public function testReservedNameIsPrefixedWithtest(): void
    {
        $name = 'isthisreserved';  // not reserved, or a test - yet
        $this->assertFalse($this->rn->isTest($name));
        $this->assertFalse($this->rn->isReserved($name));

        $name = 'test'.$name;
        // now it's a test and so is also reserved
        $this->assertTrue($this->rn->isTest($name));
        $this->assertTrue($this->rn->isReserved($name));
    }

    /**
     * Finally, a plain check against isTest() alone.
     */
    public function testCheckingAgainstIsTest(): void
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
