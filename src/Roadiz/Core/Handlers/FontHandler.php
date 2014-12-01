<?php
/**
 * Copyright © 2014, REZO ZERO
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
 * Except as contained in this notice, the name of the REZO ZERO shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from the REZO ZERO SARL.
 *
 * @file FontHandler.php
 * @copyright REZO ZERO 2014
 * @author Ambroise Maupate
 */
namespace RZ\Roadiz\Core\Handlers;

use RZ\Roadiz\Core\Entities\Font;
use RZ\Roadiz\Core\Kernel;

/**
 * Handle operations with fonts entities..
 */
class FontHandler
{
    protected $font = null;

    /**
     * @param RZ\Roadiz\Core\Entities\Font $font
     */
    public function __construct(Font $font)
    {
        $this->font = $font;
    }
    /**
     * @return RZ\Roadiz\Core\Entities\Font Current font entity
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * Generate a font download url.
     *
     * @param string $extension Select a specific font file.
     * @param string $token     Csrf token to protect from requesting font more than once.
     *
     * @return string
     */
    public function getDownloadUrl($extension, $token)
    {
        return Kernel::getService('urlGenerator')->generate(
            'FontFile',
            array(
                'filename'  => $this->font->getHash(),
                'extension' => $extension,
                'token'     => $token
            )
        );
    }
}
