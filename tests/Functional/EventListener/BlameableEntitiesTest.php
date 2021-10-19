<?php

namespace App\Tests\Functional\EventListener;

use App\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;

class BlameableEntitiesTest extends WebTestCase
{
    protected $user;
    /** @var EntityManager */
    protected $em;

    public function setUp(): void
    {
        static::bootKernel();
        $container = self::$container;
        $this->em = $container->get(EntityManagerInterface::class);
        $this->em->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
    }

    public function testPost()
    {
        $this->loginAs('admin');

        $post = (new Post())
            ->setText('Random text')
            ->setTitle('Hello world');

        $this->assertNull($post->getCreatedBy());
        $this->assertNull($post->getUpdatedBy());

        $this->em->persist($post);
        $this->em->flush();

        /** @var Post $post */
        $post = $this->em->getRepository(Post::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertEquals('admin', $post->getCreatedBy());
        $this->assertEquals('admin', $post->getUpdatedBy());
    }

    private function loginAs(string $username)
    {
        $tokenStorage = self::$container->get(TokenStorageInterface::class);
        $firewallName = 'secure_area';
        $this->user = new User($username, '123456');
        $token = new UsernamePasswordToken($this->user, null, $firewallName, ['ROLE_ADMIN']);
        $tokenStorage->setToken($token);

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request(),
            HttpKernelInterface::MASTER_REQUEST
        );
        self::$container->get('stof_doctrine_extensions.event_listener.blame')->onKernelRequest($event);
    }
}
