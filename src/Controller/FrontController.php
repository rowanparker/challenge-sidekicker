<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/html', name: 'html_index')]
    public function html_index(): Response
    {
        return $this->render('html/index.html.twig', [
            'jobs' => $this->entityManager->getRepository(Job::class)->findAll()
        ]);
    }

    #[Route('/html/show/{id}', name: 'html_show')]
    public function html_show(int $id): Response
    {
        $job = $this->entityManager->getRepository(Job::class)->find($id);

        if (null === $job) {
            throw new NotFoundHttpException();
        }

        return $this->render('html/show.html.twig', [
            'job' => $job,
        ]);
    }

    #[Route('/js', name: 'js_index')]
    public function js(): Response
    {
        return $this->render('js/index.html.twig');
    }
}