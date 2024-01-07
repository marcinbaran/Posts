<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/lista')]
class PostsController extends AbstractController
{
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_posts_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('posts/index.html.twig', [
            'posts' => $this->postRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_posts_delete', methods: ['GET', 'DELETE'])]
    public function delete(int $id): Response
    {
        $post = $this->postRepository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_posts_index');
    }
}
