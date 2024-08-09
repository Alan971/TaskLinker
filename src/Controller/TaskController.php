<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Enum\TaskStatus as EnumTaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/add/{status}', name: 'app_add_task', methods:['GET', 'POST'])]
    #[Route('/modify/{id, projectId}', requirements: ['id' => '\d+'], methods:['GET', 'POST'], name: 'app_modify_task')]
    public function addTask(?string $status, ?int $projectId, Request $request, EntityManagerInterface $manager, ?Task $task, ?Project $project): Response
    {
        $enum = new EnumTaskStatus;
        $taskStatus = $enum->getLabel();

        $form = $this->createForm(TaskType::class, $task);
        if(!isset($task)) {
            $form->get('status')->setData(EnumTaskStatus::DONE);
        }
        
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if (!$form['project']->getData()){
                $task->setProject($project);
            }
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project');
        }
        
        return $this->render('task/createModify.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }
}
