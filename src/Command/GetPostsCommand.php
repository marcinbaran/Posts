<?php

namespace App\Command;

use App\Entity\Post;
use App\Services\TypicodeApiProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-posts',
    description: 'Gets posts from API and save these to the database',
)]
class GetPostsCommand extends Command
{
    private TypicodeApiProvider $apiProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(TypicodeApiProvider $apiProvider, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->apiProvider = $apiProvider;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posts = $this->apiProvider->getApiData('posts');
        $users = $this->apiProvider->getApiData('users');

        if (!$posts || !$users) {
            return Command::FAILURE;
        }

        foreach ($posts as $post) {
            $newPost = $this->createNewPost($post, $users);
            $this->entityManager->persist($newPost);
        }

        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('Success !');

        return Command::SUCCESS;
    }

    private function getUserName(array $users, int $userId): string
    {
        foreach ($users as $user) {
            if ($userId === $user['id']) {
                return $user['name'];
            }
        }

        return '';
    }

    private function createNewPost(array $post, array $users): Post
    {
        $newPost = new Post();
        $newPost->setTitle($post['title']);
        $newPost->setBody($post['body']);
        $newPost->setUser($this->getUserName($users, $post['userId']));

        return $newPost;
    }
}
