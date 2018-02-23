<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @Route("/tasks/done", name="task_list_done")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDoneAction()
    {
        return $this->render(
            'task/list.html.twig',
            array(
                'tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->getDone(),
                'user' => $this->getUser()
            )
        );
    }

    /**
     * @Route("/tasks/todo", name="task_list_todo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listTodoAction()
    {
        return $this->render(
            'task/list.html.twig',
            array(
                'tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->getTodo(),
                'user' => $this->getUser()
            )
        );
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $authUser = $this->getUser();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $authUser->addTask($task);
            $manager->persist($task);
            $manager->persist($authUser);
            $manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list_todo');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function editAction(Task $task, Request $request)
    {
        // Checking object property with voter
        $this->denyAccessUnlessGranted('edit', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list_todo');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @param Task $task
     * @return Response
     */
    public function toggleTaskAction(Task $task)
    {
        // Checking object property with voter
        $this->denyAccessUnlessGranted('edit', $task);

        $task->toggle();
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list_todo');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @param Task $task
     * @return Response
     */
    public function deleteTaskAction(Task $task)
    {
        // Checking object property with voter
        $this->denyAccessUnlessGranted('delete', $task);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($task);
        $manager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list_todo');
    }
}
