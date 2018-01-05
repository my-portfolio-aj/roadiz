<?php
/**
 * Copyright (c) 2018. Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
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
 * @file TreeNodeModel.php
 * @author Ambroise Maupate <ambroise@rezo-zero.com>
 */

namespace Themes\Rozier\Models;

use RZ\Roadiz\Core\Entities\Document;
use RZ\Roadiz\Core\Entities\NodesSources;
use RZ\Roadiz\Core\Entities\Tag;
use RZ\Roadiz\Utils\UrlGenerators\DocumentUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Translation\Translator;

class TreeNodeModel extends NodeModel
{
    public function toArray()
    {
        /** @var Translator $trans */
        $trans = $this->container->offsetGet('translator');
        /** @var NodesSources $source */
        $source = $this->node->getNodeSources()->first();
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->container->offsetGet('urlGenerator');

        $nodeArray = [
            'id' => $this->node->getId(),
            'type' => [
                'class' => Node::class,
                'name' => $this->node->getNodeType()->getName(),
                'title' =>  $this->node->getNodeType()->getDisplayName(),
                'color' =>  $this->node->getNodeType()->getColor(),
            ],
            'node_name' => $this->node->getNodeName(),
            'title' => $source->getTitle(),
            'url' => $this->getSingleNodeUrl($source),
            'statuses' => [
                'visible' => $this->node->isVisible(),
                'hiding_children' => $this->node->isHidingChildren(),
                'locked' => $this->node->isLocked(),
                'home' => $this->node->isHome(),
                'published' => $this->node->isPublished(),
                'pending' => $this->node->isPending(),
                'draft' => $this->node->isDraft(),
                'deleted' => $this->node->isDeleted(),
                'archived' => $this->node->isArchived(),
                'publishable' => $this->node->getNodeType()->isPublishable(),
            ],
            'actions' => [
                [
                    'icon'=> 'rz-plus',
                    'url'=> $urlGenerator->generate('nodesAddChildPage', ['nodeId' => $this->node->getId()]),
                    'label'=> $trans->trans("add.node.%name%.child", ['%name%' => $source->getTitle()]),
                ],
                [
                    'icon'=> 'rz-pencil',
                    'url'=> $urlGenerator->generate('nodesEditSourcePage', [
                        'nodeId' => $this->node->getId(),
                        'translationId' => $source->getTranslation()->getId(),
                    ]),
                    'label'=> $trans->trans("edit.node.%name%", ['%name%' => $source->getTitle()]),
                ],
            ]
        ];

        if ($this->container->offsetGet('securityAuthorizationChecker')->isGranted('ROLE_ACCESS_NODES_STATUS')) {
            $nodeArray['actions'][] = [
                'icon'=> 'rz-published-mini',
                'url'=> $urlGenerator->generate('nodesPublishAllAction', [
                    'nodeId' => $this->node->getId()
                ]),
                'label'=> $trans->trans("publish_node_offspring"),
            ];
        }

        if ($this->container->offsetGet('securityAuthorizationChecker')->isGranted('ROLE_ACCESS_NODES_DELETE') &&
            !$this->node->isLocked()) {
            $nodeArray['actions'][] = [
                'icon'=> 'rz-trash-o',
                'url'=> $urlGenerator->generate('nodesDeletePage', ['nodeId' => $this->node->getId()]),
                'label'=> $trans->trans("delete.node.%name%", ['%name%' => $source->getTitle()]),
            ];
        }

        if ($this->node->getNodeType()->isPublishable() && null !== $source->getPublishedAt()) {
            $nodeArray['published_at'] = $source->getPublishedAt()->format('c');
        }

        if (null !== $source->getDocumentsByFields()->first() &&
            false !== $source->getDocumentsByFields()->first() &&
            null !== $document = $source->getDocumentsByFields()->first()->getDocument()) {
            if ($document instanceof Document && ($document->isImage() || $document->isSvg()) && !$document->isPrivate()) {
                /** @var DocumentUrlGenerator $documentUrlGenerator */
                $documentUrlGenerator = $this->container->offsetGet('document.url_generator');
                $documentUrlGenerator->setDocument($document);
                $documentUrlGenerator->setOptions([
                    'fit' => '60x60',
                    'quality' => 80,
                ]);
                $nodeArray['thumbnail'] = [
                    'mime_type' => $document->getMimeType(),
                    'filename' => $document->getFilename(),
                    'url' => $documentUrlGenerator->getUrl(),
                ];
            }
        }

        if ($this->node->getTags()->count() > 0) {
            /** @var Tag $tag */
            $tagArray = [];
            foreach ($this->node->getTags() as $tag) {
                $tagModel = new TagModel($tag, $this->container);
                $tagArray[] = $tagModel->toArray();
            }
            $nodeArray['tags'] = $tagArray;
        }

        return $nodeArray;
    }
}
