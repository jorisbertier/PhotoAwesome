<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\CategorySearchType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    public function __construct( private CategoryRepository $categoryRepository, private EntityManagerInterface $entityManager){

    }

    #[Route('/', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $qb = $this->categoryRepository->getQbAll();

        $form = $this->createForm(CategorySearchType::class);
        $form->handleRequest($request);   // écoute les globales

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($data['categoryTitle'] !== null) {
                $qb->andwhere('c.label LIKE :toto')
                ->setParameter('toto', '%'. $data['categoryTitle'] .'%');
            }
        }
        $result = $qb->getQuery()->getResult();

        return $this->render('category/index.html.twig', [
            'categories' => $result,
            'form' => $form->createView()
        ]);
    }

    #[Route('/show/{id}', name: 'app_category_show')]
    public function detail($id): Response
    {
        $categoryEntity = $this->categoryRepository->find($id);

        return $this->render('category/show.html.twig', [
            'category' => $categoryEntity,
        ]);
    }

    #[Route('/create', name: 'app_category_create')]
    public function create(Request $request): Response
    {
        
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id) : Response
    {
            $categoryEntity = $this->categoryRepository->find($id);
            $this->entityManager->remove($categoryEntity);
            $this->entityManager->flush();

        return $this->redirectToRoute('app_category');
    }

    #[Route('/edit/{id}', name: 'app_category_edit')]
    public function edit($id, Request $request): Response
    {   
        $categoryEntity = $this->categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $categoryEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($categoryEntity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $categoryEntity, // Correction du nom de la variable
            'form' => $form->createView(), // Utilisation de createView() pour le rendu du formulaire
        ]);
    }
}
