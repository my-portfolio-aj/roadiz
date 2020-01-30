<?php
declare(strict_types=1);

namespace RZ\Roadiz\Attribute\Model;

use RZ\Roadiz\Core\Entities\Translation;

interface AttributeGroupTranslationInterface
{
    /**
     * @return mixed
     */
    public function getName(): ?string;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function setName(?string $value);

    /**
     * @param Translation $translation
     *
     * @return mixed
     */
    public function setTranslation(Translation $translation);

    /**
     * @return Translation|null
     */
    public function getTranslation(): ?Translation;

    /**
     * @return AttributeGroupInterface
     */
    public function getAttributeGroup(): AttributeGroupInterface;

    /**
     * @param AttributeGroupInterface $attributeGroup
     *
     * @return mixed
     */
    public function setAttributeGroup(AttributeGroupInterface $attributeGroup);
}