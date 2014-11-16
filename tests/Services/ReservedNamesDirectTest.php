<?php
namespace Ca\ProfileBundle\Tests\Services;

use Alister\ReservedNamesBundle\Services\ReservedNames;
use Alister\ReservedNamesBundle\Services\CleanUserNames;

class ReservedNamesDirectTest extends \PHPUnit_Framework_TestCase
{
    /** @var Alister\ReservedNamesBundle\Services\ReservedNames */
    protected $rn;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->names = [
            'alister' => 1, 'website' => 1, 'private' => 1,
        ];
        // build the classes directly
        $clean = new CleanUserNames;
        $this->rn = new ReservedNames($this->names, $clean);
    }

    public function testSanityServiceClass()
    {
        $this->assertInstanceOf('Alister\ReservedNamesBundle\Services\ReservedNames', $this->rn);

        $names = $this->rn->getReservedNames();
        $this->assertInternalType('array', $names);
        $this->assertEquals($this->names, $names);

        $this->assertGreaterThanOrEqual(3, count($names));
        $this->assertCount(3, $names);
        $this->assertArrayHasKey('alister', $names);
    }

    public function testCheckingAgainstReservedName()
    {
        $this->assertTrue($this->rn->isReserved('alister'), 'alister was not reserved');
        $this->assertTrue($this->rn->isReserved('website'), 'website was not reserved');
        $this->assertTrue($this->rn->isReserved('private'), 'private was not reserved');
        $this->assertTrue($this->rn->isReserved('alister123'), 'alister123 was not reserved');

        $this->assertFalse($this->rn->isReserved('notinthelist'), 'notinthelist was marked as reserved, but its not in the list!');
    }

    public function testCheckingAgainstTest()
    {
        $this->assertTrue($this->rn->isTest('test123123'));
        $this->assertTrue($this->rn->isTest('test'));

        $this->assertFalse($this->rn->isTest('alister'));
        $this->assertFalse($this->rn->isTest('alister123'));
        $this->assertFalse($this->rn->isTest('alister123'));

        $this->assertFalse($this->rn->isTest('ali123ster'));
    }
}
