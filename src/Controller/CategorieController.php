<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// #[IsGranted('ROLE_ADMIN')]
final class CategorieController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAllCategories();
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]

    #[Route('/category/new', name: 'app_category_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
        }

         return $this->render('categories/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
