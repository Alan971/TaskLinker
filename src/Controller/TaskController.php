<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/add', name: 'app_add_task', methods:['GET', 'POST'])]
    #[Route('/modify/{id}', requirements: ['id' => '\d+'], methods:['GET', 'POST'], name: 'app_modify_task')]
    public function addTask(Request $request, EntityManagerInterface $manager, ?Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isValid() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project');
        }
        
        return $this->render('task/createModify.html.twig', [
            'task' => $task,
        ]);
    }
}
