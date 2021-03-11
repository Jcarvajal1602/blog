<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response //mostrar posts de todos paginados
    {
        $user = $this->getUser(); // obtiene al usuario actualmente logueado
        if ($user) {
            $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
        $query = $em->getRepository(Posts::class)->BuscarTodosLosPosts(); // traer registros de la base de datos
        //$post = $em->getRepository(Posts::class)->findAll(); // traer registros de la base de datos
        
        //crear paginacion de resultados
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );
        return $this->render('dashboard/index.html.twig', [
            'pagination' => $pagination
        ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
        
    }
}
