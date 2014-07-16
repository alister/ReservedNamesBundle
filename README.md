# ReservedNamesBundle - alister/reserved-names-bundle

A bundle to clean, and check, a given username against an (extensible) list of reserved words/usernames

# Installation and use

1. Add to app/AppKernel.php

    $bundles = array(
        new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        // ... many others

        new Alister\ReservedNamesBundle\AlisterReservedNamesBundle(),
    );

2. Add to app/config/config.yml

    alister_reserved_names:
        names:
            # These keys will be lower-cased
            - myname
            - myothername
            - alister
            - private

3. Use
        
    $username = 'myname_123';
    $reserved = $this->container->get('alister_reserved_names.check');
    if ($reserved->isReserved($username)) {
        echo "{$username} is reserved";
    }

4. Services provided:

* alister_reserved_names.check 
  * Check usernanme does not match a reserved name, before or after calling @cleanusername
  * @see Alister\ReservedNamesBundle\Services\ReservedNames
* alister_reserved_names.cleanusername
  * remove 'noise characters' around the given username
  * EG: myname_123 becomes myname
  * @see Alister\ReservedNamesBundle\Services\CleanUserNames

## Included tests

Testing is done with the classes directly, and also via a container, to test the service initialisation.

## How to create a test setup for a local test of the service

http://blog.kevingomez.fr/2013/01/09/functional-testing-standalone-symfony2-bundles/

## @todo

Make a validator (from existing code) and put into this bundle, with tests and examples
See: http://stackoverflow.com/questions/7004601/symfony2-testing-entity-validation-constraints


## Badges

[![Build Status](https://travis-ci.org/alister/reserved-names-bundle.svg?branch=master)](https://travis-ci.org/alister/reserved-names-bundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8be6b1cb-f48d-4447-b9b4-682f549aa40c/mini.png)](https://insight.sensiolabs.com/projects/8be6b1cb-f48d-4447-b9b4-682f549aa40c) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alister/reserved-names-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alister/reserved-names-bundle/?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/53c05854638521361000009b/badge.svg)](https://www.versioneye.com/user/projects/53c05854638521361000009b)

[![Latest Stable Version](https://poser.pugx.org/alister/reserved-names-bundle/v/stable.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![Total Downloads](https://poser.pugx.org/alister/reserved-names-bundle/downloads.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![Latest Unstable Version](https://poser.pugx.org/alister/reserved-names-bundle/v/unstable.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![License](https://poser.pugx.org/alister/reserved-names-bundle/license.svg)](https://packagist.org/packages/alister/reserved-names-bundle)
