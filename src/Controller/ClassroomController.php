<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddclassroomType;



class ClassroomController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    #[Route('/classroom/list/{id}', name: 'classroom-id')]
    public function FindById(ManagerRegistry $doctrine, int $id): Response
    {
        $Classroom = $doctrine->getRepository(Classroom::class)->find($id);

        if (!$Classroom) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return new Response('Class: '.$Classroom->getName());

    }
    #[Route('/classroom/list', name: 'classroom-list')]
    public function List(ManagerRegistry $doctrine): Response
    {
        $Classrooms = $doctrine->getRepository(Classroom::class)->findAll();
        return $this -> render('classroom/list.html.twig',["Classrooms"=>$Classrooms]);
    }

    #[Route('/classroom/add', name: 'classroom-add')]
    public function addClassroom(ManagerRegistry $doctrine, Request $request): Response
    {
        $classroom = new Classroom() ;
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('read_classroom');
        }
        return $this->renderForm("classroom/add.html.twig",
            ["f"=>$form]) ;


    }
    }

    
    #[Route('/classroom/add2', name: 'add2')]
     function addClassroom2(ManagerRegistry $doctrine, Request $request): Response
    {
        $classroom = new Classroom();
        $form= $this->createForm(AddclassroomType::class,$classroom);
        return $this -> render('classroom/add2.html.twig',[ 'controller_name' => 'ClassroomController']);
    }


    #[Route('/classroom/addclassroom', name: 'add-classroom')]
      function addClassroomPost(ManagerRegistry $doctrine, Request $request): Response
    {
        $classroom = new Classroom();
        $Issn =trim($request->get('Name'));
        $parameters = json_decode($request->getContent(), true);
        $entityManager = $doctrine->getManager();
        $classroom->setName($Issn);
        $entityManager->persist($classroom);
        $entityManager->flush();
        return $this -> render('classroom/add2.html.twig',[ 'controller_name' => 'ClassroomController']); 
    }



