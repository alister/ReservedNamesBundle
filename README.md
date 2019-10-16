# ReservedNamesBundle - alister/reserved-names-bundle

A bundled service to clean, and check, a given username against an (extensible) list of reserved words/usernames.

Note: This DOES NOT validate usernames. That should happen (and potentially disallow names) before getting to this stage.

# Installation and use

1. Add to app/AppKernel.php

    ```php
    $bundles = array(
        new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        // ... many others

        new Alister\ReservedNamesBundle\AlisterReservedNamesBundle(),
    );
    ```

2. Add to app/config/config.yml

    ```yaml
    alister_reserved_names:
        names:
            # These keys will be lower-cased
            - myname
            - myothername
            - alister
            - private
    ```

3. Use
        
    ```php
    $username = 'myname_123';
    $reserved = $this->container->get('alister_reserved_names.check');
    if ($reserved->isReserved($username)) {
        echo "{$username} is reserved";
    }
    ```

4. Services provided:

* alister_reserved_names.check 
  * Check username does not match a reserved name, before or after calling @cleanusername
  * @see Alister\ReservedNamesBundle\Services\ReservedNames
  * The code also strips off trailing 's', and then additional noise characters (digits, -, _) for a final test against the reserved names
* alister_reserved_names.cleanusername
  * remove 'noise characters' around the given username
  * EG: myname_123 becomes myname
  * @see Alister\ReservedNamesBundle\Services\CleanUserNames

## Included tests

Testing is done with the classes directly, and also via a container, to test the service initialisation. This also allows a check for the 'local reservations' - extra names that can be defined in the local application. The container-based test includes a micro-application to build the container, and so run the full test. [How to create a test setup for a local test of the service](http://blog.kevingomez.fr/2013/01/09/functional-testing-standalone-symfony2-bundles/).

## @todo

Make a validator (from existing code) and put into this bundle, with tests and examples
See: http://stackoverflow.com/questions/7004601/symfony2-testing-entity-validation-constraints


## Badges

[![Build Status](https://travis-ci.org/alister/ReservedNamesBundle.svg?branch=master)](https://travis-ci.org/alister/ReservedNamesBundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8be6b1cb-f48d-4447-b9b4-682f549aa40c/mini.png)](https://insight.sensiolabs.com/projects/8be6b1cb-f48d-4447-b9b4-682f549aa40c) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alister/ReservedNamesBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alister/ReservedNamesBundle/?branch=master)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Falister%2FReservedNamesBundle.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Falister%2FReservedNamesBundle?ref=badge_shield)

[![Latest Stable Version](https://poser.pugx.org/alister/reserved-names-bundle/v/stable.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![Total Downloads](https://poser.pugx.org/alister/reserved-names-bundle/downloads.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![Latest Unstable Version](https://poser.pugx.org/alister/reserved-names-bundle/v/unstable.svg)](https://packagist.org/packages/alister/reserved-names-bundle) [![License](https://poser.pugx.org/alister/reserved-names-bundle/license.svg)](https://packagist.org/packages/alister/reserved-names-bundle)


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Falister%2FReservedNamesBundle.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Falister%2FReservedNamesBundle?ref=badge_large)