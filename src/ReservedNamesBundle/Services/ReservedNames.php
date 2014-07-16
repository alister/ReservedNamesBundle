<?php
namespace Alister\ReservedNamesBundle\Services;

use Alister\ReservedNamesBundle\Services\CleanUserNamesInterface;

class ReservedNames implements ReservedNamesInterface
{
    /** @var array of usernames to chack against */
    private $reservedNames;

    /** @var CleanUserNamesInterface class to return a 'clean' username */
    private $cleanUsername;

    public function __construct(array $reservedNames, CleanUserNamesInterface $cleanUsername)
    {
        if (! $this->reservedNames) {
            $this->reservedNames = $reservedNames;
        }
        $this->cleanUsername = $cleanUsername;
    }

    /**
     * Allow the reserved username list to be extracted.
     *
     * The inbuild list is written all in lower-case, but any new ones are 
     * down-cased as part of the bundle configuration.
     * 
     * @return array list of usernames ['username' => 1, ...]
     */
    public function getReservedNames()
    {
        return $this->reservedNames;
    }

    /**
     * A ReservedName is one based on a list - we also strip numbers and '-_' chars
     *
     * @param string  $username [description]
     *
     * @return boolean Is the username reserved?
     */
    public function isReserved($username)
    {
        if (array_key_exists($username, $this->reservedNames)) {
            return true;
        }

        $altUsername = $this->cleanUsername->clean($username);
        if ($altUsername != $username and array_key_exists($altUsername, $this->reservedNames)) {
            return true;
        }

        return false;
    }

    /**
     * Convenience function - oes the post-cleaned username start with 'test' ?
     * 
     * @param string username to clean and check
     * 
     * @return boolean true if the first 4 'real' chars are 'test'
     */
    public function isTest($username)
    {
        $altUsername = $this->cleanUsername->clean($username);

        if ('test' == substr($altUsername, 0, 4)) {
            // first 4 chars of the 'cleaned' username are 'test'
            return true;
        }
        return false;
    }
}
