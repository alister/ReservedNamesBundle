<?php
namespace Alister\ReservedNamesBundle\Services;

interface ReservedNamesInterface
{
    public function getReservedNames();
    public function isReserved($username);
    public function isTest($username, $alreadyClean = false);
}
