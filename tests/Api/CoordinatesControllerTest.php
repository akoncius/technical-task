<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoordinatesControllerTest extends WebTestCase
{
    public function testCoordinates()
    {
        $client = static::createClient();

        $response = $client->request('GET', '/coordinates?country=germany&city=berlin&street=Heinrich-Heine-Strasse 24');

        $this->assertResponseIsSuccessful();
    }
}
