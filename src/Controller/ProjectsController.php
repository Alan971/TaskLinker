<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\ProjectType;
use Doctrine\Common\Collections\Collection as CollectionsCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Collection as TypesCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Collection;


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
    public function createModifyPj(?int $id, Request $request, EntityManagerInterface $manager, ?Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            
            // si le projet est nouveau, alors on cree un nouvel objet
            if(!isset($project)) {
                $newProject = new Project;
                $newProject->setName($form->get('name')->getData());
                foreach ($form->get('pjAccess')->getData() as $employee) {
                    $newProject->addPjAccess($employee);
                }
                $manager->persist($newProject);
                $manager->flush();
            }
            else {
                // si le projet n'est pas nouveau, on supprime toutes les relations de la base employée_projet
                
                $oldProject = new Project;
                $oldProject = $manager->getRepository(Project::class)->findById($id);
                $employees = $oldProject->getPjAccess();
                foreach($employees as $employee) {
                    $project->removePjAccess($employee);
                }
                // avant de remettre les nouvelles
                $manager->persist($project);
                $manager->flush();
            }


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
            $tasks = $manager->getRepository(Task::class)->findByPjId($project->getId());
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
