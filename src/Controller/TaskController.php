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

use function PHPUnit\Framework\isEmpty;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/add/{status}/{projectId}', requirements: ['projectId' => '\d+'], name: 'app_add_task', methods:['GET', 'POST'])]
    #[Route('/modify/{id}', requirements: ['id' => '\d+'], methods:['GET', 'POST'], name: 'app_modify_task')]
    public function addTask(?int $id, ?string $status, ?int $projectId, Request $request, EntityManagerInterface $manager, ?Task $task, ?Project $project): Response
    {

        // gestion de la route add :
        // gestion de l'affichage par défaut du select en fonction du type de tache sélectionnée
        // récupération de l'object projet à partir de son id
        if(isset($status) && isset($projectId)) {
            $project = $manager->getRepository(Project::class)->findById($projectId);
            switch ($status) {
                case 'To Do':
                    $status = EnumTaskStatus::TODO;
                    break;
                case 'Doing' :
                    $status = EnumTaskStatus::DOING;
                    break;
                case 'Done' :
                    $status = EnumTaskStatus::DONE;
                    break;
                default:
                    $status = EnumTaskStatus::TODO;
                    break;
            }
            $task = new Task;
        }
        // chemin modify
        // si ProjectId n'est pas récupéré dans le chemin c'est que c'est une tache déjà existante
        // on récupère alors l'Id du projet
        if (!$projectId){
            $project = $task->getProject();
            $projectId = $project->getId();
        }

        $form = $this->createForm(TaskType::class, $task);
        if(isEmpty($task)) {
            $form->get('status')->setData($status);
            $form->get('project_id')->setData($projectId);
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_view_project', ['id' => $projectId]);
        }
        return $this->render('task/createModify.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }
}
