<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class ProjectsController extends AbstractController
{
    #[Route('/', name: 'app_projects')]
    public function index(EntityManagerInterface $manager): Response
    {
        $projects = $manager->getRepository(Project::class)->findAll();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }


    #[Route('/{/id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_addModify_project')]
    public function createModifyPj(Request $request, EntityManagerInterface $manager): Response
    {
        $project ??= new Project;
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_projects');
        }
        return $this->render('projects/createModify.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{/id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_archive_project')]
    public function archivePj(EntityManagerInterface $manager, Project $project): Response
    {
        if(isset($project)) {
            $project->setArchive(true);
            $manager->persist($project);
            $manager->flush();
        }
        return $this->redirectToRoute('app_projects');
    }

    #[Route('/{/id}', requirements:['id' => '\d+'], methods:['GET', 'POST'], name: 'app_view_project')]
    public function viewPj(EntityManagerInterface $manager, Project $project): Response
    {
        if(isset($project)) {
            // recherche des task et mise en tableau
            $tasks = [];

            return $this->render('projects/view.html.twig', [
                'project' => $project,
                'tasks' => $tasks,
            ]);

        }
        return $this->redirectToRoute('app_projects');
    }

}
