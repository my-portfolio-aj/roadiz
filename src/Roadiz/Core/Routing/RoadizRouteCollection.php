<?php
declare(strict_types=1);
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
 * @file RoadizRouteCollection.php
 * @author Ambroise Maupate
 */
namespace RZ\Roadiz\Core\Routing;

use RZ\Roadiz\CMS\Controllers\AssetsController;
use RZ\Roadiz\Core\Bags\Settings;
use RZ\Roadiz\Core\Entities\Theme;
use RZ\Roadiz\Utils\Theme\ThemeResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class RoadizRouteCollection.
 *
 * @package RZ\Roadiz\Core\Routing
 */
class RoadizRouteCollection extends DeferredRouteCollection
{
    /**
     * @var Stopwatch
     */
    protected $stopwatch;
    /**
     * @var ThemeResolverInterface
     */
    protected $themeResolver;
    /**
     * @var bool
     */
    private $isPreview;
    /**
     * @var Settings
     */
    private $settingsBag;

    /**
     * @param ThemeResolverInterface $themeResolver
     * @param Settings $settingsBag
     * @param Stopwatch $stopwatch
     * @param bool $isPreview
     */
    public function __construct(
        ThemeResolverInterface $themeResolver,
        Settings $settingsBag,
        Stopwatch $stopwatch = null,
        $isPreview = false
    ) {
        $this->stopwatch = $stopwatch;
        $this->themeResolver = $themeResolver;
        $this->isPreview = $isPreview;
        $this->settingsBag = $settingsBag;
    }

    /**
     * {@inheritdoc}
     */
    public function parseResources(): void
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start('routeCollection');
        }

        $resources = $this->getResources();
        /*
         * Adding Backend routes
         */
        $this->addBackendCollection();

        /*
         * Add Assets controller routes
         */
        $assets = AssetsController::getRoutes();
        $staticDomain = $this->settingsBag->get('static_domain_name');
        if (false === $this->isPreview &&
            false !== $staticDomain &&
            '' != $staticDomain) {
            /*
             * Only use CDN if no preview mode and CDN domain is well set
             * Remove protocol (https, http and protocol-less) information from domain.
             */
            $host = parse_url($staticDomain, PHP_URL_HOST);
            if (false !== $host && null !== $host) {
                $assets->setHost($host);
            } else {
                $assets->setHost($staticDomain);
            }
            /*
             * ~~Use same scheme as static domain.~~
             *
             * DO NOT use setSchemes method as it need a special UrlMatcher
             * only available on Symfony full-stack
             */
        }
        $this->addCollection($assets);

        /*
         * Add Frontend routes
         *
         * return 'RZ\Roadiz\CMS\Controllers\FrontendController';
         */
        $this->addThemesCollections();

        if (null !== $this->stopwatch) {
            $this->stopwatch->stop('routeCollection');
        }
    }

    protected function addBackendCollection(): void
    {
        $class = $this->themeResolver->getBackendClassName();
        if (class_exists($class)) {
            $collection = call_user_func([$class, 'getRoutes']);
            if (null !== $collection) {
                $this->addCollection($collection);
            }
        } else {
            throw new \RuntimeException("Class “" . $class . "” does not exist.", 1);
        }
    }

    protected function addThemesCollections(): void
    {
        $frontendThemes = $this->themeResolver->getFrontendThemes();
        foreach ($frontendThemes as $theme) {
            if ($theme instanceof Theme) {
                $feClass = $theme->getClassName();
                /** @var RouteCollection $feCollection */
                $feCollection = call_user_func([$feClass, 'getRoutes']);
                /** @var RouteCollection $feBackendCollection */
                $feBackendCollection = call_user_func([$feClass, 'getBackendRoutes']);

                if ($feCollection !== null) {
                    // set host pattern if defined
                    if ($theme->getHostname() != '*' &&
                        $theme->getHostname() != '') {
                        $feCollection->setHost($theme->getHostname());
                    }
                    /*
                     * Add a global prefix on theme static routes
                     */
                    if ($theme->getRoutePrefix() != '') {
                        $feCollection->addPrefix($theme->getRoutePrefix());
                    }
                    $this->addCollection($feCollection);
                }

                if ($feBackendCollection !== null) {
                    /*
                     * Do not prefix or hostname admin routes.
                     */
                    $this->addCollection($feBackendCollection);
                }
            } else {
                throw new \RuntimeException("Object of type “" . get_class($theme) . "” does not extend RZ\\Roadiz\\Core\\Entities\\Theme class.", 1);
            }
        }
    }
}
