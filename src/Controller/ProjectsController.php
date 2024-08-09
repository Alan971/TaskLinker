<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class ProjectsController extends AbstractController
{
    #[Route('', name: 'app_projects')]
    public function index(EntityManagerInterface $manager): Response
    {
        $projects = $manager->getRepository(Project::class)->findAll();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('new/', methods:['GET', 'POST'], name: 'app_add_project')]
    #[Route('modify/{id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_modify_project')]
    public function createModifyPj(Request $request, EntityManagerInterface $manager, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_projects');
        }
        return $this->render('projects/createModify.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('archive/{id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_archive_project')]
    public function archivePj(EntityManagerInterface $manager, Project $project): Response
    {
        if(isset($project)) {
            $project->setArchive(true);
            $manager->persist($project);
            $manager->flush();
        }
        return $this->redirectToRoute('app_projects');
    }

    #[Route('view/{id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_view_project')]
    public function viewPj(EntityManagerInterface $manager, ?Project $project): Response
    {
        if(isset($project)) {
            // recherche des task et mise en tableau
            $tasks = [];
            // recherche des employés
            $employees = $project->getPjAccess();
            
            return $this->render('projects/view.html.twig', [
                'project' => $project,
                'tasks' => $tasks,
                'employees' => $employees,
            ]);

        }
        return $this->redirectToRoute('app_projects');
    }

}
