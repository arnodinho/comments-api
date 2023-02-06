<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends  WebTestCase
{
    public function test_index(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/home');

        static::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
