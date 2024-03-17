<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepo, Request $request): Response
    {
        $currentPage = $request->query->get('page') ? $request->query->get('page') : 1;
        $size = $request->query->get('size') ? $request->query->get('size') : 10;
        $allowedTableSize = [10,20,50,100];

        $basicSort = [
            [
                'label' => 'Croissant',
                'icon' => '<i class="bi bi-sort-alpha-down"></i>'
            ],
            [
                'label' => 'Décroissant',
                'icon' => '<i class="bi bi-sort-alpha-up"></i>'
            ]
        ];
            

        $tableColumns = [
            [
                'label' => '#',
                'sort' => $basicSort,
                'activated' => false
            ],
            [
                'label' => 'Firstname',
                'sort' => $basicSort
            ],
            [
                'label' => 'Lastname',
                'sort' => $basicSort
            ],
            [
                'label' => 'Email',
                'sort' => $basicSort
            ],
            [
                'label' => 'Banned',
                'sort' => $basicSort
            ],
            [
                'label' => 'Created',
                'sort' => $basicSort
            ],
            [
                'label' => 'Updated',
                'sort' => $basicSort
            ]
        ];
        

        if (!in_array($size, $allowedTableSize)){
            //error size not allowed
            $size = 10;
        }

        $paginator = $userRepo->getPaginator($currentPage, $size);

        return $this->render('user/index.html.twig', [
            'users' => $paginator,
            'currentPage' => $currentPage,
            'nbPage' => ($paginator->count() / $size),
            'size' => $size,
            'allowedTableSize' => $allowedTableSize,
            'tableColumns' => $tableColumns
        ]);
    }

    #[Route('/toggle-user', name: 'app_user_toggle')]
    public function toggleUser(UserRepository $userRepo, Request $request, EntityManagerInterface $em): Response
    {

        $userId = $request->query->get('user_id');
        $page = $request->query->get('page');

        $user = $userRepo->find($userId);
        $user->setDisabled(!$user->isDisabled());

        $em->persist($user);
        $em->flush();

        if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('turbo/refresh_row.html.twig', [
                'rowId' => $userId
            ]);

        }
        $this->addFlash('success', 'suppression réussie');
        return $this->redirectToRoute('app_user', ['page' => $page]);
        
    }

}
