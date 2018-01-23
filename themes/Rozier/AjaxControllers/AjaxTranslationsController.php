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
 * @file AjaxTranslationsController.php
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

namespace Themes\Rozier\AjaxControllers;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

class AjaxTranslationsController extends AjaxAbstractFieldsController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAction (Request $request) {
        $_locale = 'en';

        if ($request->query->has('_locale')) {
            $_locale = $request->query->get('_locale');
        }

        $translator = new Translator($_locale);
        $translator->setFallbackLocales(['en']);
        $translator->addLoader('xlf', new XliffFileLoader());

        $translationPathFile = static::getResourcesFolder() . '/translations/messages.' . $_locale . '.xlf';

        if (!file_exists($translationPathFile)) {
            throw new BadRequestHttpException('Locale message does not exist: ' . $translationPathFile);
        }

        $translator->addResource('xlf', $translationPathFile, $_locale);

        $translations = [
            'locale' => $_locale,
            'defaultDomains' => 'message',
            'fallback' => 'en',
            'translations' => [],
        ];

        $translator->setLocale($_locale);
        $translations['translations'][$_locale] = $translator->getCatalogue()->all();
        $response = $this->renderJson($translations);

        return $response;
    }
}
