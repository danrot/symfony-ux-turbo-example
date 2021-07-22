<?php
namespace App\Controller;

use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    public function form(Request $request): Response
    {
        // just setup a fresh $task object (remove the example data)
        $task = [];

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('form_success', ['task' => $task['task']]);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function formSuccess(Request $request): Response
    {
        return $this->render('form_success.html.twig', ['task' => $request->query->get('task')]);
    }
}
