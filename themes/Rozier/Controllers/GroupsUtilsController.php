<?php
/*
 * Copyright © 2014, Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name of the ROADIZ shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
 *
 *
 * @file GroupsUtilsController.php
 * @author Thomas Aufresne
 */

namespace Themes\Rozier\Controllers;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use RZ\Roadiz\CMS\Importers\GroupsImporter;
use RZ\Roadiz\Core\Entities\Group;
use RZ\Roadiz\Core\Serializers\GroupCollectionJsonSerializer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Themes\Rozier\RozierApp;

/**
 * {@inheritdoc}
 */
class GroupsUtilsController extends RozierApp
{
    /**
     * Export all Group datas and roles in a Json file (.rzt).
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAllAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ACCESS_GROUPS');

        $existingGroup = $this->get('em')
                              ->getRepository(Group::class)
                              ->findAll();

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        return new JsonResponse(
            $serializer->serialize(
                $existingGroup,
                'json',
                SerializationContext::create()->setGroups(['group'])
            ),
            JsonResponse::HTTP_OK,
            [
                'Content-Disposition' => sprintf('attachment; filename="%s"', 'group-all-' . date("YmdHis") . '.json'),
            ],
            true
        );
    }

    /**
     * Export a Group in a Json file (.rzt).
     *
     * @param Request $request
     * @param int     $groupId
     *
     * @return Response
     */
    public function exportAction(Request $request, $groupId)
    {
        $this->denyAccessUnlessGranted('ROLE_ACCESS_GROUPS');

        $existingGroup = $this->get('em')
                              ->find(Group::class, (int) $groupId);

        if (null === $existingGroup) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        return new JsonResponse(
            $serializer->serialize(
                $existingGroup,
                'json',
                SerializationContext::create()->setGroups(['group'])
            ),
            JsonResponse::HTTP_OK,
            [
                'Content-Disposition' => sprintf('attachment; filename="%s"', 'group-' . $existingGroup->getName() . '-' . date("YmdHis") . '.json'),
            ],
            true
        );
    }

    /**
     * Import a Json file (.rzt) containing Group datas and roles.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importJsonFileAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ACCESS_GROUPS');

        $form = $this->buildImportJsonFileForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid() &&
            !empty($form['group_file'])) {
            /** @var UploadedFile $file */
            $file = $form['group_file']->getData();

            if ($form->isSubmitted() && $file->isValid()) {
                $serializedData = file_get_contents($file->getPathname());

                if (null !== json_decode($serializedData)) {
                    $this->get(GroupsImporter::class)->import($serializedData);
                    $this->get('em')->flush();

                    $msg = $this->getTranslator()->trans('group.imported.updated');
                    $this->publishConfirmMessage($request, $msg);

                    // redirect even if its null
                    return $this->redirect($this->generateUrl(
                        'groupsHomePage'
                    ));
                }
                $form->addError(new FormError($this->getTranslator()->trans('file.format.not_valid')));
            } else {
                $form->addError(new FormError($this->getTranslator()->trans('file.not_uploaded')));
            }
        }

        $this->assignation['form'] = $form->createView();

        return $this->render('groups/import.html.twig', $this->assignation);
    }

    /**
     * @return FormInterface
     */
    private function buildImportJsonFileForm()
    {
        $builder = $this->createFormBuilder()
                        ->add('group_file', FileType::class, [
                            'label' => 'group.file',
                        ]);

        return $builder->getForm();
    }
}
