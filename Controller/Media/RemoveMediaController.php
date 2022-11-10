<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RemoveMediaController extends AbstractController
{
    private $translator;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(TranslatorInterface $translator, ParameterBagInterface $parameterBag)
    {
        $this->translator = $translator;
        $this->parameterBag = $parameterBag;
    }

    public function index(Media $media)
    {
        if (empty($media)) {
            throw new NotFoundHttpException("Page content not found");
        }

        $mediaName = $media->getName();
        $em = $this->getDoctrine()->getManager();

        try {
            // remove server files
            $paths = [
                $media->getPath(),
                $media->getLargePath(),
                $media->getMediumPath(),
                $media->getSmallPath()
            ];

            foreach ($paths as $filePath) {
                if (empty($filePath)) continue;

                $filePath = $this->parameterBag->get("reaccion_cms_admin.upload_dir") . $filePath;

                if (!file_exists($filePath)) continue;

                unlink($filePath);
            }

            $em->remove($media);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('media_detail.removed_media_successful'));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get("reaccion_cms.logger")->logException($e, "Error removing media entity.");

            $errorMssg = $this->translator->trans(
                'media_detail.removed_media_error',
                [
                    '%name%' => $mediaName,
                    '%error%' => $e->getMessage()
                ]
            );

            $this->addFlash('error', $errorMssg);
        } catch (\Exception $e) {
            $this->get("reaccion_cms.logger")->logException($e, "Error removing media files.");

            $errorMssg = $this->translator->trans(
                'media_detail.removed_media_error',
                [
                    '%name%' => $mediaName,
                    '%error%' => $e->getMessage()
                ]
            );

            $this->addFlash('error', $errorMssg);
        }

        return $this->redirectToRoute('reaccion_cms_admin_media_index');
    }
}
