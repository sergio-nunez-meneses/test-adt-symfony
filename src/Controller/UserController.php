<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $response;

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->user_repository()->findAll();

        if (empty($users))
        {
            $this->response = ['error' => 'No users found.'];
        }
        else
        {
            $this->response = ['users' => $users];
        }

        return $this->render_response('index', $this->response);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $user = $this->user_repository()->find($id);

        if (!$user)
        {
            $this->response = ['error' => "User with id $id not found"];
        }
        else
        {
            $this->response = ['user' => $user];
        }

        return $this->render_response('show', $this->response);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(int $id) : Response
    {
        $user = $this->user_repository()->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        $this->response = [
            'user' => $user,
            'form' => $form->createView()
        ];

        return $this->render_response('edit', $this->response);
    }

    private function user_repository()
    {
        return $this->getDoctrine()
            ->getRepository(User::class);
    }

    private function render_response($view, $response)
    {
        return $this->render("user/$view.html.twig", $response);
    }
}
