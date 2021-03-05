<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;

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
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

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
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

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

    private function render_response($view, $response)
    {
        return $this->render("user/$view.html.twig", $response);
    }
}
