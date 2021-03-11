<?php

namespace App\Controller;

use App\Entity\Comentarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ComentariosType;


class ComentariosController extends AbstractController
{
    /**
     * @Route("/comentarios", name="comentarios")
     */
    public function index(Request $request): Response
    {
        $comentarios= new Comentarios();
        $form = $this->createForm(ComentariosType::class, $comentarios); //crear formulario con los campos
        $form->handleRequest($request); //determina si el formulario fue enviado
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
            $em->persist($comentarios);
            $em->flush();
            $this->addFlash('exito', Comentarios::COMENTARIO_EXITOSO);
            return $this->redirectToRoute('home');
        }

        return $this->render('comentarios/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
