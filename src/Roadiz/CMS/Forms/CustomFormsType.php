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
 * @file CustomFormsType.php
 * @author Maxime Constantinian
 */
namespace RZ\Roadiz\CMS\Forms;

use RZ\Roadiz\CMS\Forms\Constraints\Recaptcha;
use RZ\Roadiz\CMS\Forms\NodeSource\NodeSourceType;
use RZ\Roadiz\Core\AbstractEntities\AbstractField;
use RZ\Roadiz\Core\Entities\CustomForm;
use RZ\Roadiz\Core\Entities\CustomFormField;
use RZ\Roadiz\Core\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CustomFormsType
 * @package RZ\Roadiz\CMS\Forms
 */
class CustomFormsType extends AbstractType
{
    /**
     * @param  FormBuilderInterface $builder
     * @param  array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldsArray = $this->getFieldsByGroups($options);

        /** @var CustomFormField|array $field */
        foreach ($fieldsArray as $group => $field) {
            if ($field instanceof CustomFormField) {
                $this->addSingleField($builder, $field, $options);
            } elseif (is_array($field)) {
                $slugger = new AsciiSlugger();
                $groupCanonical = $slugger->slug($group)->toString();
                $subBuilder = $builder->create($groupCanonical, FormType::class, [
                    'label' => $group,
                    'inherit_data' => true,
                    'attr' => [
                        'data-group-wrapper' => $groupCanonical,
                    ]
                ]);
                /** @var CustomFormField $subfield */
                foreach ($field as $subfield) {
                    $this->addSingleField($subBuilder, $subfield, $options);
                }
                $builder->add($subBuilder);
            }
        }

        /*
         * Add Google Recaptcha if setting optionnal options.
         */
        if (!empty($options['recaptcha_public_key']) &&
            !empty($options['recaptcha_private_key']) &&
            !empty($options['request'])) {
            $verifyUrl = !empty($options['recaptcha_verifyurl']) ?
                $options['recaptcha_verifyurl'] :
                'https://www.google.com/recaptcha/api/siteverify';

            $builder->add('recaptcha', RecaptchaType::class, [
                'label' => false,
                'configs' => [
                    'publicKey' => $options['recaptcha_public_key'],
                ],
                'constraints' => [
                    new Recaptcha($options['request'], [
                        'privateKey' => $options['recaptcha_private_key'],
                        'verifyUrl' => $verifyUrl,
                    ]),
                ],
            ]);
        }
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getFieldsByGroups(array $options)
    {
        $fieldsArray = [];
        $fields = $options['customForm']->getFields();

        /** @var CustomFormField $field */
        foreach ($fields as $field) {
            if ($field->getGroupName() != '') {
                if (!isset($fieldsArray[$field->getGroupName()])) {
                    $fieldsArray[$field->getGroupName()] = [];
                }
                $fieldsArray[$field->getGroupName()][] = $field;
            } else {
                $fieldsArray[] = $field;
            }
        }

        return $fieldsArray;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param CustomFormField $field
     * @param array $formOptions
     * @return $this
     */
    protected function addSingleField(FormBuilderInterface $builder, CustomFormField $field, array $formOptions)
    {
        $builder->add($field->getName(), $this->getTypeForField($field), $this->getOptionsForField($field, $formOptions));
        return $this;
    }

    /**
     * @param CustomFormField $field
     * @return MarkdownType|string
     */
    protected function getTypeForField(CustomFormField $field)
    {
        switch ($field->getType()) {
            case AbstractField::ENUM_T:
            case AbstractField::MULTIPLE_T:
                return ChoiceType::class;
                break;
            case AbstractField::DOCUMENTS_T:
                return FileType::class;
                break;
            case AbstractField::MARKDOWN_T:
                return MarkdownType::class;
                break;
            default:
                return NodeSourceType::getFormTypeFromFieldType($field);
        }
    }

    /**
     * @param CustomFormField $field
     * @param array $formOptions
     * @return array
     */
    protected function getOptionsForField(CustomFormField $field, array $formOptions)
    {
        $option = [
            "label" => $field->getLabel(),
            'help' => $field->getDescription(),
            'attr' => [
                'data-group' => $field->getGroupName(),
            ],
        ];

        if ($field->getPlaceholder() !== '') {
            $option['attr']['placeholder'] = $field->getPlaceholder();
        }

        if ($field->isRequired()) {
            $option['required'] = true;
            $option['constraints'] = [
                new NotBlank([
                    'message' => 'you.need.to.fill.this.required.field'
                ])
            ];
        } else {
            $option['required'] = false;
        }

        switch ($field->getType()) {
            case AbstractField::ENUM_T:
                if ($field->getPlaceholder() !== '') {
                    $option['placeholder'] = $field->getPlaceholder();
                }
                $option["choices"] = $this->getChoices($field);
                $option["expanded"] = $field->isExpanded();

                if ($formOptions['forceExpanded']) {
                    $option["expanded"] = true;
                }
                if ($field->isRequired() === false) {
                    $option['placeholder'] = 'none';
                }
                break;
            case AbstractField::MULTIPLE_T:
                if ($field->getPlaceholder() !== '') {
                    $option['placeholder'] = $field->getPlaceholder();
                }
                $option["choices"] = $this->getChoices($field);
                $option["multiple"] = true;
                $option["expanded"] = $field->isExpanded();

                if ($formOptions['forceExpanded']) {
                    $option["expanded"] = true;
                }
                if ($field->isRequired() === false) {
                    $option['placeholder'] = 'none';
                }
                break;
            case AbstractField::DOCUMENTS_T:
                $option['required'] = false;
                break;
            case AbstractField::COUNTRY_T:
                $option["expanded"] = $field->isExpanded();
                if ($field->getPlaceholder() !== '') {
                    $option['placeholder'] = $field->getPlaceholder();
                }
                if ($field->getDefaultValues() !== '') {
                    $countries = explode(',', $field->getDefaultValues());
                    $countries = array_map('trim', $countries);
                    $option['preferred_choices'] = $countries;
                }
                break;
            case AbstractField::EMAIL_T:
                if (!isset($option['constraints'])) {
                    $option['constraints'] = [];
                }
                $option['constraints'][] = new Email();
                break;
            default:
                break;
        }

        return $option;
    }

    /**
     * @param CustomFormField $field
     * @return array
     */
    protected function getChoices(CustomFormField $field)
    {
        $choices = explode(',', $field->getDefaultValues());
        $choices = array_map('trim', $choices);
        $choices = array_combine(array_values($choices), array_values($choices));

        return $choices;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'recaptcha_public_key' => null,
            'recaptcha_private_key' => null,
            'recaptcha_verifyurl' => null,
            'request' => null,
            'forceExpanded' => false,
        ]);

        $resolver->setRequired('customForm');

        $resolver->setAllowedTypes('customForm', [CustomForm::class]);
        $resolver->setAllowedTypes('forceExpanded', ['boolean']);
        $resolver->setAllowedTypes('request', [Request::class, 'null']);
        $resolver->setAllowedTypes('recaptcha_public_key', ['string', 'null', 'boolean']);
        $resolver->setAllowedTypes('recaptcha_private_key', ['string', 'null', 'boolean']);
        $resolver->setAllowedTypes('recaptcha_verifyurl', ['string', 'null', 'boolean']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'custom_form_public';
    }
}
