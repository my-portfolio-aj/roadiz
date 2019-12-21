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
 * @file Document.php
 * @author Ambroise Maupate
 */
namespace RZ\Roadiz\Core\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use RZ\Roadiz\Core\Models\AbstractDocument;
use RZ\Roadiz\Core\Models\AdvancedDocumentInterface;
use RZ\Roadiz\Core\Models\DocumentInterface;
use RZ\Roadiz\Core\Models\FolderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use function Symfony\Component\String\u;

/**
 * Documents entity represent a file on server with datetime and naming.
 *
 * @ORM\Entity(repositoryClass="RZ\Roadiz\Core\Repositories\DocumentRepository")
 * @ORM\Table(name="documents", indexes={
 *     @ORM\Index(columns={"raw"}),
 *     @ORM\Index(columns={"private"}),
 *     @ORM\Index(columns={"mime_type"})
 * })
 */
class Document extends AbstractDocument implements AdvancedDocumentInterface
{
    /**
     * @ORM\OneToOne(targetEntity="Document", inversedBy="downscaledDocument", cascade={"all"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="raw_document", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"document"})
     * @Serializer\Type("RZ\Roadiz\Core\Entities\Document")
     * @var DocumentInterface|null
     */
    protected $rawDocument = null;
    /**
     * @ORM\Column(type="boolean", name="raw", nullable=false, options={"default" = false})
     * @Serializer\Groups({"document"})
     * @Serializer\Type("bool")
     */
    protected $raw = false;
    /**
     * @ORM\Column(type="string", name="embedId", unique=false, nullable=true)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    protected $embedId = null;
    /**
     * @ORM\Column(type="string", name="embedPlatform", unique=false, nullable=true)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    protected $embedPlatform = null;
    /**
     * @ORM\OneToMany(targetEntity="RZ\Roadiz\Core\Entities\NodesSourcesDocuments", mappedBy="document")
     * @var ArrayCollection
     * @Serializer\Exclude
     */
    protected $nodesSourcesByFields = null;
    /**
     * @ORM\OneToMany(targetEntity="RZ\Roadiz\Core\Entities\TagTranslationDocuments", mappedBy="document")
     * @var ArrayCollection
     * @Serializer\Exclude
     */
    protected $tagTranslations = null;
    /**
     * @ORM\ManyToMany(targetEntity="RZ\Roadiz\Core\Entities\Folder", mappedBy="documents")
     * @ORM\JoinTable(name="documents_folders")
     * @Serializer\Groups({"document"})
     * @Serializer\Type("ArrayCollection<RZ\Roadiz\Core\Entities\Folder>")
     */
    protected $folders;
    /**
     * @ORM\OneToMany(targetEntity="DocumentTranslation", mappedBy="document", orphanRemoval=true, fetch="EAGER")
     * @var ArrayCollection
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("ArrayCollection<RZ\Roadiz\Core\Entities\DocumentTranslation>")
     */
    protected $documentTranslations;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    private $filename;
    /**
     * @ORM\Column(name="mime_type", type="string", nullable=true)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    private $mimeType;
    /**
     * @ORM\OneToOne(targetEntity="Document", mappedBy="rawDocument")
     * @Serializer\Exclude
     * @var DocumentInterface|null
     */
    private $downscaledDocument = null;
    /**
     * @ORM\Column(type="string")
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    private $folder;
    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" = false})
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("bool")
     */
    private $private = false;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("int")
     */
    private $imageWidth = 0;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("int")
     */
    private $imageHeight = 0;
    /**
     * @var string|null
     * @ORM\Column(type="string", name="average_color", length=7, unique=false, nullable=true)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("string")
     */
    private $imageAverageColor;
    /**
     * @var int|null The filesize in bytes.
     * @ORM\Column(type="integer", nullable=true, unique=false)
     * @Serializer\Groups({"document", "nodes_sources", "tag"})
     * @Serializer\Type("int")
     */
    private $filesize;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->folders = new ArrayCollection();
        $this->documentTranslations = new ArrayCollection();
        $this->nodesSourcesByFields = new ArrayCollection();
        $this->tagTranslations = new ArrayCollection();
        $this->imageWidth = 0;
        $this->imageHeight = 0;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename($filename)
    {
        $slugger = new AsciiSlugger();
        $tokens = u($filename)->split('.');
        $extension = \array_pop($tokens);
        $this->filename = $slugger->slug(u('_')->join($tokens), '_') . '.' . $extension;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set folder name.
     *
     * @param string $folder
     * @return $this
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmbedId()
    {
        return $this->embedId;
    }

    /**
     * @param string $embedId
     * @return $this
     */
    public function setEmbedId($embedId)
    {
        $this->embedId = $embedId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmbedPlatform()
    {
        return $this->embedPlatform;
    }

    /**
     * @param string $embedPlatform
     * @return $this
     */
    public function setEmbedPlatform($embedPlatform)
    {
        $this->embedPlatform = $embedPlatform;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param boolean $private
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = (boolean) $private;
        if (null !== $raw = $this->getRawDocument()) {
            $raw->setPrivate($private);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNodesSourcesByFields()
    {
        return $this->nodesSourcesByFields;
    }

    /**
     * @return ArrayCollection
     */
    public function getTagTranslations()
    {
        return $this->tagTranslations;
    }

    /**
     * @param FolderInterface $folder
     * @return $this
     */
    public function addFolder(FolderInterface $folder)
    {
        if (!$this->getFolders()->contains($folder)) {
            $this->folders->add($folder);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param FolderInterface $folder
     * @return $this
     */
    public function removeFolder(FolderInterface $folder)
    {
        if ($this->getFolders()->contains($folder)) {
            $this->folders->remove($folder);
        }

        return $this;
    }

    /**
     * @param Translation $translation
     * @return Collection
     */
    public function getDocumentTranslationsByTranslation(Translation $translation)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('translation', $translation));

        return $this->documentTranslations->matching($criteria);
    }

    /**
     * @param DocumentTranslation $documentTranslation
     * @return $this
     */
    public function addDocumentTranslation(DocumentTranslation $documentTranslation)
    {
        if (!$this->getDocumentTranslations()->contains($documentTranslation)) {
            $this->documentTranslations->add($documentTranslation);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDocumentTranslations()
    {
        return $this->documentTranslations;
    }

    /**
     * @return bool
     */
    public function hasTranslations()
    {
        return (boolean) $this->getDocumentTranslations()->count();
    }

    /**
     * Gets the value of rawDocument.
     *
     * @return DocumentInterface|null
     */
    public function getRawDocument()
    {
        return $this->rawDocument;
    }

    /**
     * Sets the value of rawDocument.
     *
     * @param DocumentInterface|null $rawDocument the raw document
     *
     * @return self
     */
    public function setRawDocument(DocumentInterface $rawDocument = null)
    {
        $this->rawDocument = $rawDocument;

        return $this;
    }

    /**
     * Is document a raw one.
     *
     * @return boolean
     */
    public function isRaw()
    {
        return $this->raw;
    }

    /**
     * Sets the value of raw.
     *
     * @param boolean $raw the raw
     *
     * @return self
     */
    public function setRaw($raw)
    {
        $this->raw = (boolean) $raw;

        return $this;
    }

    /**
     * Gets the downscaledDocument.
     *
     * @return DocumentInterface|null
     */
    public function getDownscaledDocument()
    {
        return $this->downscaledDocument;
    }

    /**
     * @return int
     */
    public function getImageWidth(): int
    {
        return $this->imageWidth;
    }

    /**
     * @param int $imageWidth
     *
     * @return Document
     */
    public function setImageWidth(int $imageWidth)
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageHeight(): int
    {
        return $this->imageHeight;
    }

    /**
     * @param int $imageHeight
     *
     * @return Document
     */
    public function setImageHeight(int $imageHeight)
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getImageRatio(): ?float
    {
        if ($this->getImageWidth() > 0 && $this->getImageHeight() > 0) {
            return $this->getImageWidth() / $this->getImageHeight();
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getImageAverageColor(): ?string
    {
        return $this->imageAverageColor;
    }

    /**
     * @param string|null $imageAverageColor
     *
     * @return Document
     */
    public function setImageAverageColor(?string $imageAverageColor)
    {
        $this->imageAverageColor = $imageAverageColor;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    /**
     * @param int|null $filesize
     * @return Document
     */
    public function setFilesize(?int $filesize)
    {
        $this->filesize = $filesize;
        return $this;
    }

    /**
     * Clone current document.
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $this->rawDocument = null;
        }
    }
}
