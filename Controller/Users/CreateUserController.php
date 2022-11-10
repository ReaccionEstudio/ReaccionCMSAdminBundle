<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Users;

use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Users\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateUserController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();

        // form parameters
        $userRoles = $this->getParameter("reaccion_cms_admin.roles");

        // form
        $form = $this->createForm(UserType::class, $user, ['roles' => $userRoles]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // set password
                $user->setPlainPassword($form['userPassword']->getData());

                // save
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', $this->translator->trans('users_form.create_success_message'));

                return $this->redirectToRoute('reaccion_cms_admin_users_index');
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans('users_form.create_error_message', [
                        '%name%' => $form['username']->getData(),
                        '%error%' => $e->getMessage()
                    ])
                );
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/users/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'create'
            ]
        );
    }
}
