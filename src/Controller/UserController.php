<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\UserType;
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
        $users = $this->user_repository('all');

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
        $user = $this->user_repository($id);

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
        $user = $this->user_repository($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest(Request::createFromGlobals());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get_entity_manager()->flush();

            return $this->redirectToRoute('user_index');
        }

        $this->response = [
            'user' => $user,
            'form' => $form->createView()
        ];

        return $this->render_response('edit', $this->response);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(int $id) : Response
    {
        $user = $this->user_repository($id);
        $request = Request::createFromGlobals()->request;
        $token = 'delete'. $user->getId();

        if ($this->isCsrfTokenValid($token, $request->get('_token'))) {
            $em = $this->get_entity_manager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    private function get_entity_manager()
    {
        return $this->getDoctrine()->getManager();
    }

    private function user_repository($query)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        if (gettype($query) === 'string' && $query === 'all')
        {
            return $repository->findAll();
        }
        elseif (gettype($query) === 'integer' && $query > 0)
        {
            return $repository->find($query);
        }
    }

    private function render_response($view, $response)
    {
        return $this->render("user/$view.html.twig", $response);
    }
}
