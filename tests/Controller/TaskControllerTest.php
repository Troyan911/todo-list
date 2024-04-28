<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Add your task');
    }

    public function testCanSeeTasks(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task');

        $this->assertCount(5, $crawler->filter("p a"));
    }

    public function testCanAddTask(): void {
        $taskTitle = 'Created from test task';

        $client = static::createClient();
        $crawler = $client->request('GET', '/task');

        $client->submitForm('Submit', [
            'task[title]' => $taskTitle
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorExists("a:contains('$taskTitle')");
    }

    public function testCanGoToNextPage(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task');

        $client->clickLink('Next');
        $this->assertSelectorNotExists("a:contains('Task 0')");
        $this->assertSelectorNotExists("a:contains('Next')");
        $this->assertSelectorExists("a:contains('Task 5')");
        $this->assertSelectorExists("a:contains('Previous')");
    }
}
