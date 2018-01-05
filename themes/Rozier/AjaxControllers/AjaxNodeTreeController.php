<?php
/**
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
 * @file AjaxNodeTreeController.php
 * @author Ambroise Maupate
 */
namespace Themes\Rozier\AjaxControllers;

use RZ\Roadiz\Core\Entities\Node;
use RZ\Roadiz\Core\Entities\Tag;
use RZ\Roadiz\Core\Entities\Translation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Themes\Rozier\Models\TreeNodeModel;
use Themes\Rozier\Widgets\NodeTreeWidget;

/**
 * {@inheritdoc}
 */
class AjaxNodeTreeController extends AbstractAjaxController
{
    /**
     * @param Request $request
     * @return Translation
     */
    protected function getTranslationFromRequest(Request $request)
    {
        if ($request->query->has('translation_id')) {
            $translation = $this->get('em')
                ->find(
                    Translation::class,
                    (int) $request->query->get('translation_id')
                );

            if (null !== $translation) {
                return $translation;
            }
        }

        return $this->get('defaultTranslation');
    }

    /**
     * @param Request $request
     * @return Tag|null
     */
    protected function getTagFromRequest(Request $request)
    {
        if ($request->query->has('tag_id')) {
            $tag = $this->get('em')
                ->find(
                    Tag::class,
                    (int) $request->query->get('tag_id')
                );

            if (null !== $tag) {
                return $tag;
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @return Node|null
     */
    protected function getParentLeafFromRequest(Request $request)
    {
        if ($request->query->has('parent_id')) {
            $parentLeaf = $this->get('em')
                ->find(
                    Node::class,
                    (int) $request->query->get('parent_id')
                );

            if (null !== $parentLeaf) {
                return $parentLeaf;
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function jsonTreeAction(Request $request)
    {
        $this->validateAccessForRole('ROLE_ACCESS_NODES');

        $translation = $this->getTranslationFromRequest($request);
        $parentNode = $this->getParentLeafFromRequest($request);
        $tag = $this->getTagFromRequest($request);

        if (null === $parentNode && null !== $this->getUser()) {
            $parentNode = $this->getUser()->getChroot();
        }

        $nodeTree = new NodeTreeWidget(
            $this->getRequest(),
            $this,
            $parentNode,
            $translation
        );
        if (null !== $tag) {
            $nodeTree->setTag($tag);
        }
        if (true === (boolean) $request->get('stack_tree')) {
            $nodeTree->setStackTree(true);
        }

        return $this->renderJson([
            'name' => '',
            'type' => 'node-tree',
            'items' => $this->getNodesArray($nodeTree),
            'langs' => $this->getAvailableTranslationsUrl($nodeTree),
            'statuses' => [
                'can_reorder' => $nodeTree->getCanReorder(),
            ]
        ]);
    }

    protected function getNodesArray(NodeTreeWidget $nodeTreeWidget)
    {
        $items = [];
        $firstNodes = $nodeTreeWidget->getChildrenNodes($nodeTreeWidget->getRootNode());
        foreach ($firstNodes as $node) {
            $items[] = $this->getSingleNodeArray($node, $nodeTreeWidget);
        }

        return $items;
    }

    /**
     * @param Node $node
     * @param NodeTreeWidget $nodeTreeWidget
     * @return array
     */
    protected function getSingleNodeArray(Node $node, NodeTreeWidget $nodeTreeWidget)
    {
        $model = new TreeNodeModel($node, $this->getContainer());
        $nodeArray = $model->toArray();
        $nodeArray['children'] = [];

        /*
         * Display children only if node is note a stack and
         * its node-type does not hide children
         */
        if (!$node->isHidingChildren() && !$node->getNodeType()->isHidingNodes()) {
            $children = $nodeTreeWidget->getChildrenNodes($node);
            if (count($children) > 0) {
                $childrenArray = [];
                foreach ($children as $child) {
                    $childrenArray[] = $this->getSingleNodeArray($child, $nodeTreeWidget);
                }

                $nodeArray['children'] = $childrenArray;
            }
        }

        return $nodeArray;
    }

    /**
     * @param NodeTreeWidget $nodeTreeWidget
     * @return array
     */
    protected function getAvailableTranslationsUrl(NodeTreeWidget $nodeTreeWidget)
    {
        $langs = [];
        $baseParams = [];

        if (null !== $nodeTreeWidget->getRootNode()) {
            $baseParams['parent_id'] = $nodeTreeWidget->getRootNode()->getId();
        }
        if (null !== $nodeTreeWidget->getTag()) {
            $baseParams['tag_id'] = $nodeTreeWidget->getTag()->getId();
        }
        /** @var Translation $availableTranslation */
        foreach ($nodeTreeWidget->getAvailableTranslations() as $availableTranslation) {
            $langs[] = [
                'name' => $availableTranslation->getName(),
                'locale' => $availableTranslation->getPreferredLocale(),
                'url' => $this->generateUrl('nodesTreeJson', array_merge([
                    'translation_id' => $availableTranslation->getId(),
                ], $baseParams)),
            ];
        }

        return $langs;
    }


    /**
     * @param Request $request
     * @param null $translationId
     * @return JsonResponse
     */
    public function getTreeAction(Request $request, $translationId = null)
    {
        $this->validateAccessForRole('ROLE_ACCESS_NODES');

        if (null === $translationId) {
            $translation = $this->get('defaultTranslation');
        } else {
            $translation = $this->get('em')
                                ->find(
                                    '\RZ\Roadiz\Core\Entities\Translation',
                                    (int) $translationId
                                );
        }

        /** @var NodeTreeWidget|null $nodeTree */
        $nodeTree = null;

        switch ($request->get("_action")) {
            /*
             * Inner node edit for nodeTree
             */
            case 'requestNodeTree':
                if ($request->get('parentNodeId') > 0) {
                    $node = $this->get('em')
                                 ->find(
                                     '\RZ\Roadiz\Core\Entities\Node',
                                     (int) $request->get('parentNodeId')
                                 );
                } elseif (null !== $this->getUser()) {
                    $node = $this->getUser()->getChroot();
                } else {
                    $node = null;
                }

                $nodeTree = new NodeTreeWidget(
                    $this->getRequest(),
                    $this,
                    $node,
                    $translation
                );

                if ($request->get('tagId') &&
                    $request->get('tagId') > 0) {
                    $filterTag = $this->get('em')
                                        ->find(
                                            '\RZ\Roadiz\Core\Entities\Tag',
                                            (int) $request->get('tagId')
                                        );

                    $nodeTree->setTag($filterTag);
                }

                $this->assignation['mainNodeTree'] = false;

                if (true === (boolean) $request->get('stackTree')) {
                    $nodeTree->setStackTree(true);
                }
                break;
            /*
             * Main panel tree nodeTree
             */
            case 'requestMainNodeTree':
                $parent = null;
                if (null !== $this->getUser()) {
                    $parent = $this->getUser()->getChroot();
                }

                $nodeTree = new NodeTreeWidget(
                    $this->getRequest(),
                    $this,
                    $parent,
                    $translation
                );
                $this->assignation['mainNodeTree'] = true;
                break;
        }

        $this->assignation['nodeTree'] = $nodeTree;

        $responseArray = [
            'statusCode' => '200',
            'status' => 'success',
            'nodeTree' => $this->getTwig()->render('widgets/nodeTree/nodeTree.html.twig', $this->assignation),
        ];

        return new JsonResponse(
            $responseArray,
            Response::HTTP_OK
        );
    }
}
