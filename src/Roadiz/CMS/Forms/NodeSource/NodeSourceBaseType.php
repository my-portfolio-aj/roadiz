<?php
/**
 * Copyright (c) 2017. Ambroise Maupate and Julien Blanchet
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
 * @file NodeSourceBaseType.php
 * @author Ambroise Maupate <ambroise@rezo-zero.com>
 */

namespace RZ\Roadiz\CMS\Forms\NodeSource;

use RZ\Roadiz\Core\Entities\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class NodeSourceBaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'title',
            'required' => false,
            'attr' => [
                'data-dev-name' => '{{ nodeSource.title }}',
                'lang' => strtolower(str_replace('_', '-', $options['translation']->getLocale())),
                'dir' => $options['translation']->isRtl() ? 'rtl' : 'ltr',
            ],
            'constraints' => [
                new Length([
                    'max' => 255,
                ])
            ]
        ]);

        if ($options['publishable'] === true) {
            $builder->add('publishedAt', DateTimeType::class, [
                'label' => 'publishedAt',
                'required' => false,
                'attr' => [
                    'class' => 'rz-datetime-field',
                    'data-dev-name' => '{{ nodeSource.publishedAt }}',
                ],
                'date_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'placeholder' => [
                    'hour' => 'hour',
                    'minute' => 'minute',
                ],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nodesourcebase';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'inherit_data' => true,
            'publishable' => false,
        ]);

        $resolver->setRequired('translation');

        $resolver->setAllowedTypes('publishable', 'boolean');
        $resolver->setAllowedTypes('translation', Translation::class);
    }
}
