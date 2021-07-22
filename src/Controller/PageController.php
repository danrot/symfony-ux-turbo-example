<?php
namespace App\Controller;

use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function about(Request $request): Response
    {
        return $this->renderForTurbo($request, 'about.html.twig', []);
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

        return $this->renderForTurbo($request, 'form.html.twig', ['form' => $form->createView()]);
    }

    public function formSuccess(Request $request): Response
    {
        return $this->renderForTurbo($request, 'form_success.html.twig', ['task' => $request->query->get('task')]);
    }

    private function renderForTurbo(Request $request, string $template, array $parameters)
    {
        if ($request->headers->get('Turbo-Frame') === 'content') {
            $template = $this->get('twig')->load($template);

            return new Response($template->renderBlock('content', $parameters));
        }

        return $this->render($template, $parameters);
    }
}
