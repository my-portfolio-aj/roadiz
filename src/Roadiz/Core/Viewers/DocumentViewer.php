<?php
declare(strict_types=1);
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
 * @file DocumentViewer.php
 * @author Ambroise Maupate
 */
namespace RZ\Roadiz\Core\Viewers;

use RZ\Roadiz\Core\Entities\Document;
use RZ\Roadiz\Core\Models\DocumentInterface;

/**
 * Class DocumentViewer
 * @package RZ\Roadiz\Core\Viewers
 * @deprecated Use ChainRenderer
 */
class DocumentViewer extends AbstractDocumentViewer
{
    /**
     * @inheritDoc
     */
    protected function getDocumentAlt()
    {
        if ($this->document instanceof Document &&
            false !== $this->document->getDocumentTranslations()->first()) {
            return $this->document->getDocumentTranslations()->first()->getName();
        }

        return "";
    }

    /**
     * @inheritDoc
     */
    protected function getTemplatesBasePath()
    {
        return "documents";
    }

    /**
     * @inheritDoc
     */
    protected function getDocumentsByFilenames($filenames): array
    {
        return $this->entityManager
            ->getRepository(Document::class)
            ->findBy(["filename" => $filenames]);
    }

    /**
     * @inheritDoc
     *
     * @param array|string $filenames
     * @return Document|null
     */
    public function getOneDocumentByFilenames($filenames): ?DocumentInterface
    {
        return $this->entityManager
            ->getRepository(Document::class)
            ->findOneBy([
                "filename" => $filenames,
                "raw" => false,
            ]);
    }
}
