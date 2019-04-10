<?php

use App\DataFixtures\TrickFixture;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DoctrineContext implements Context
{
    private $entityManager;
    private $container;
    private $passwordEncoder;

    /**
     * DoctrineContext constructor.
     *
     * @param ContainerInterface           $container
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @BeforeScenario
     *
     * @throws ToolsException
     */
    public function initDatabase()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $this->loadFixtures();
    }

    protected function loadFixtures()
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);

        $dataFixtures = new TrickFixture($this->passwordEncoder);
        $loader = new Loader();
        $loader->addFixture($dataFixtures);

        $executor->execute($loader->getFixtures());
    }
}
