<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

trait EntityManagerAwareFunctionalTestTrait
{
    /**
     * @return EntityManager
     */
    public function getNewEntityManager()
    {
        $serviceManager = Bootstrap::getServiceManager();

        /** @var EntityManager $entityManager */
        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        $classes    = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        return $entityManager;
    }
}
