<?php
namespace Alister\ReservedNamesBundle\Services;

/**
 * Remove characters around the username to get to the essence of the username.
 *
 * This means any whitespace, or numbers. I'm assuming that usernames can only
 * contain characters in the set [a-zA-Z01-9_-], and will start with -_ or alpha
 */
class CleanUserNames implements CleanUserNamesInterface
{
    /**
     * Strip numbers and '-_' chars from around a usename.
     *
     *  * __php-33_   => php
     *  * __php_hello => php_hello
     *
     * @param string $username string to trim down
     *
     * @return string 'clean' username without some chars around the first 'word'
     */
    public function clean($username)
    {
        $username = strtolower($username);
        // remove leading and trailing digits/-_
        $username = trim($username, " \t\n\r\0\x0B0123456789-_");

        // now only return a string only up to the first number/-_ char
        $lenOfAlts = strcspn($username, '0123456789_-');
        if ($lenOfAlts > 1 && strlen($username) > $lenOfAlts) {
            $username = substr($username, 0, $lenOfAlts);
        }

        return $username;
    }
}
