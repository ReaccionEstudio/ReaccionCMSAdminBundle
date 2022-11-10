<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuDetailController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(Menu $menu, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // form params
        $formParams = ['mode' => 'edit'];

        // form
        $form = $this->createForm(MenuType::class, $menu, $formParams);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // generate slug
                $menu->setSlug($menu->getName());

                // save
                $em->persist($menu);
                $em->flush();

                // flash message
                $this->addFlash('success', $this->translator->trans('menu_form.update_success_message'));
                return $this->redirectToRoute('reaccion_cms_admin_appearance_menu');
            } catch (\Exception $e) {
                $errMssg = $this->translator->trans(
                    "menu_form.update_error_message",
                    array(
                        '%name%' => $form['name']->getData(),
                        '%error%' => $e->getMessage()
                    )
                );
                $this->addFlash('error', $errMssg);
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/menu/form.html.twig",
            [
                'form' => $form->createView(),
                'menu' => $menu,
                'mode' => 'edit'
            ]
        );
    }
}
