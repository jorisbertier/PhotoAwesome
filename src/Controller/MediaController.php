<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Form\MediaSearchType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/media')]
class MediaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MediaRepository $mediaRepository,
        private PaginatorInterface $paginator
    ){

    }

    #[Route('/', name: 'app_media')]
    public function index(MediaRepository $mediaRepository, Request $request): Response
    {

        $qb = $this->mediaRepository->getQbAll();

        $form = $this->createForm(MediaSearchType::class);
        $form->handleRequest($request);   // écoute les globales

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($data['mediaTitle'] !== null) {
                $qb->andwhere('m.slug LIKE :toto')
                ->setParameter('toto', '%'. $data['mediaTitle'] .'%');
            }
            if($data['userEmail'] !== null) {
                $qb->innerJoin('m.user', 'u')
                ->andWhere('u.email = :email')
                ->setParameter('email', $data['userEmail']);
            }
            if($data['searchDate'] !== null) {
                $qb->andWhere('m.createAt >= :createAt')
                ->setParameter('createAt', $data['searchDate']);
            }
        }

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1), // réxupérer le get
            15                                  // nbr element par page
        );

        return $this->render('media/index.html.twig', [
            'medias' => $pagination,
            'form' => $form->createView()
        ]);
    }

    #[Route('/create', name: 'app_media_create')]
    public function create(Request $request): Response
    {

        $user = $this->getUser();

        if($user === null){
            return $this->redirectToRoute('app_media');
        }

        $media = new Media();
        $media->setCreateAt(new \DateTime());
        $user = $this->getUser();
        $media->setUser($user);


        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->entityManager->persist($media);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_media');
        }

        return $this->render('media/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
