<?php
/**
 * Copyright © 2015, Ambroise Maupate and Julien Blanchet
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
 * @file UniqueFolderNameValidator.php
 * @author Ambroise Maupate
 */
namespace RZ\Roadiz\CMS\Forms\Constraints;

use Doctrine\ORM\EntityManager;
use RZ\Roadiz\Core\Entities\Folder;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueFolderNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($constraint instanceof UniqueFolderName) {
            /*
             * If value is already the node name
             * do nothing.
             */
            if (null !== $constraint->currentValue && $value == $constraint->currentValue) {
                return;
            }

            if (null !== $constraint->entityManager) {
                if (true === $this->entityExists($value, $constraint->entityManager)) {
                    $this->context->addViolation($constraint->message);
                }
            } else {
                $this->context->addViolation('UniqueFolderNameValidator constraint requires a valid EntityManager');
            }
        }
    }

    /**
     * @param string $name
     * @param EntityManager $entityManager
     *
     * @return bool
     */
    protected function entityExists(string $name, EntityManager $entityManager)
    {
        $entity = $entityManager->getRepository(Folder::class)->findOneByFolderName($name);

        return (null !== $entity);
    }
}
