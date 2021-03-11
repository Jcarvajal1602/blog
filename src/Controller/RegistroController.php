<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response //registrar usuarios
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user); //crear formulario con los campos
        $form->handleRequest($request); //determina si el formulario fue enviado
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
            $user->setPassword($passwordEncoder->encodePassword($user, $form['password']->getData()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('exito', User::REGISTRO_EXITOSO);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'RegistroController',
            'formulario' => $form->createView(),
        ]);
    }
}
