<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


class PostsController extends AbstractController
{
    /**
     * @Route("/registrar-posts", name="registrar-posts")
     */
    public function index(Request $request, SluggerInterface $slugger): Response //crear post nuevo
    {
        $post= new Posts();
        $form = $this->createForm(PostsType::class, $post); //crear formulario con los campos
        $form->handleRequest($request); //determina si el formulario fue enviado
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form['foto']->getData();

            // subir imagen
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups a ocurrido un error...');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }
        
            $user= $this->getUser();
            $post->setUser($user);
            $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
            $em->persist($post);
            $em->flush();
            $this->addFlash('exito', Posts::POST_EXITOSO);
            return $this->redirectToRoute('MisPosts');
        }
        return $this->render('posts/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/post/{id}", name="Verpost")
     */

    public function VerPost($id){ //ver post completo
        
        $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
        $user= $this->getUser();
        $user = $em->getRepository(User::class)->find(['id' => $user]);
        $post = $em->getRepository(Posts::class)->find($id);
        return $this->render('posts/verPost.html.twig',['post' =>$post, 'user' => $user]);
    }

    /**
     * @Route("Mis-Posts", name="MisPosts")
     */

    public function MisPost(){ //listar post usuario
        $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
        $user= $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user' => $user], ['id' => 'DESC']);
        return $this->render('posts/MisPost.html.twig',['posts' =>$posts]);
    }
   /**
     * @Route("/editando/{id}", name="modificando")
     */
    public function editando(Request $request, Posts $post, $id, SluggerInterface $slugger) //editar mis post
    {
        //$post= new Posts();
        $form = $this->createForm(PostsType::class, $post); //crear formulario con los campos
        $form->handleRequest($request); //determina si el formulario fue enviado
        $em = $this->getDoctrine()->getManager(); //persistir entidad guardar editar y eliminar
        $post =$em->getRepository(Posts::class)->find($id);
        
        if($form->isSubmitted() && $form->isValid()){

            $brochureFile = $form['foto']->getData();
              // subir imagen
              if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups a ocurrido un error...');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }
            

            $post->setTitulo($form['titulo']->getData());
            
            $post->setContenido($form['contenido']->getData());

            $em->flush();

            $this->addFlash('exito', Posts::POST_EDITADO_EXITOSO);
            return $this->redirectToRoute('MisPosts');
        }
   
    
    return $this->render('posts/editando.html.twig', [
        'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{id}", name="eliminar")
     */
    public function borrando($id) //eliminar mis post
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->find($id);
        $em->remove($post);
        $em->flush();

         $this->addFlash('exito', Posts::POST_ELIMINANDO_EXITOSO);
        return $this->redirectToRoute('MisPosts');
    }










}
