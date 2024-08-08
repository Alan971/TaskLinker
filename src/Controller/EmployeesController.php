<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employees')]
class EmployeesController extends AbstractController
{
    #[Route('/', name: 'app_employees')]
    public function index(EntityManagerInterface $manager): Response
    {
        $employees = $manager->getRepository(Employee::class)->findAll();
        
        return $this->render('employees/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/{id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_modify_employee')]
    public function modifyEmployee(Request $request, EntityManagerInterface $manager, ?Employee $employee): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $employee->setName($form->name);
            $manager->flush();

            return $this->redirectToRoute('app_employees');
        }
        return $this->render('employees/modify.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }  
    #[Route('/{/id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_suppr_employee')]
    public function supprEmployee(EntityManagerInterface $manager, Employee $employee): Response
    {
            if(isset($employee)) {
                $manager->remove($employee);
                $manager->flush();
            }
            return $this->redirectToRoute('app_employees');
    }     
}

