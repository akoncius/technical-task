<?php

declare(strict_types=1);

namespace App\Tests\Cases;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer;

class BaseWebTestCase extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }
        $this->clearCache();
    }

    protected function truncateEntities(array $entities)
    {
        /** @var EntityManager $em */
        $em = $this->getService('doctrine')->getManager();
        $connection = $em->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $em->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    protected function clearCache(): void
    {
        /** @var Psr6CacheClearer $poolClearer */
        $poolClearer = $this->getService('cache.default_clearer');
        $poolClearer->clear('');
    }

    protected function getService(string $id): object
    {
        return self::getContainer()->get($id);
    }

    protected function makeRequest(string $method, string $url, array $data = [], array $headers = []): Response
    {
        $allHeaders = array_merge([
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], $headers);

        $this->client->request($method, $url, [], [], $allHeaders, json_encode($data));

        return $this->client->getResponse();
    }
}
