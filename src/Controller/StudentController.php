<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Student;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddStudentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $students = $doctrine->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig',
            ["s" => $students]);
    }


    #[Route('/student/add', name: 'add_student')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $student = new Student() ;
        $form = $this->createForm(AddStudentType::class, $student);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('app_student');
        }
        return $this->renderForm("student/add.html.twig",
            ["f"=>$form]) ;


    }
    #[Route('/student/update/{id}', name: 'update_student')]
    public function  update(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $student = $doctrine
        ->getRepository(Student::class)
        ->find($id);
        $form = $this->createForm(AddStudentType::class, $student);
        $form->add('update', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { 
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_student');
        }
        return $this->renderForm("student/update.html.twig",
            ["f"=>$form]) ;


    }
    #[Route('/student/delete/{id}', name: 'delete_student')]
    public function  delete(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    {   $student = $doctrine
        ->getRepository(Student::class)
        ->find($id);

       
            $em = $doctrine->getManager();
            $em->remove($student);
            $em->flush();
            return $this->redirectToRoute('app_student');

    }
}
