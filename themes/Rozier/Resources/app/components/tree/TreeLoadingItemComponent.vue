<!--
  - Copyright (c) 2017. Ambroise Maupate and Julien Blanchet
  -
  - Permission is hereby granted, free of charge, to any person obtaining a copy
  - of this software and associated documentation files (the "Software"), to deal
  - in the Software without restriction, including without limitation the rights
  - to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  - copies of the Software, and to permit persons to whom the Software is furnished
  - to do so, subject to the following conditions:
  - The above copyright notice and this permission notice shall be included in all
  - copies or substantial portions of the Software.
  -
  - THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
  - OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  - FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
  - THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  - LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  - OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
  - IN THE SOFTWARE.
  -
  - Except as contained in this notice, the name of the ROADIZ shall not
  - be used in advertising or otherwise to promote the sale, use or other dealings
  - in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
  -
  - @file TreeLoadingItemComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="background-masker-wrapper">
        <div class="background-masker-container">
            <div class="background-masker-inner">
                <div class="background-masker header-top"></div>
                <div class="background-masker header-bottom"></div>
                <div class="background-masker header-left"></div>
                <div class="background-masker header-right"></div>
                <div class="background-masker rounded rounded-1"></div>
            </div>
            <div class="background-masker footer-bottom"></div>
        </div>
        <div class="background-masker-children">
            <slot></slot>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    @import '../../scss/variables';

    $backgroundMaskerColor: $silver;

    .background-masker-wrapper {
        position: relative;
    }

    .background-masker-container {
        position: relative;
        height: 16px;
    }

    .background-masker-inner {
        position: relative;
        height: 12px;
    }

    .background-masker-children {
        margin-left: 18px;

        &::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: -30px;
            width: 30px;
            background-color: $backgroundMaskerColor;

            #main-content-scrollable & {
                background: #F0F0F0;
            }
        }

        .background-masker.header-right {
            width: 15%;
        }

        .background-masker-children {
            .background-masker.header-right {
                width: 0;
            }
        }
    }

    .background-masker {
        background: $backgroundMaskerColor;
        position: absolute;

        #main-content-scrollable & {
            background: #F0F0F0;
        }

        &.rounded {
            height: 12px;
            width: 12px;
            left: 0;
            top: 0;
            background: radial-gradient(circle closest-side at center, rgba(0,0,0,0) 100%, $backgroundMaskerColor);

            #main-content-scrollable & {
                background: radial-gradient(circle closest-side at center, rgba(0,0,0,0) 100%, #F0F0F0);
            }
        }

        &.footer-bottom {
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        &.header-top {
            top: 0;
            left: 12px;
            right: 0;
            height: 3px;
        }

        &.header-bottom {
            bottom: 0;
            left: 12px;
            right: 0;
            height: 3px;
        }

        &.header-left {
            top: 0;
            bottom: 0;
            left: 12px;
            height: 12px;
            width: 6px;
        }

        &.header-right {
            top: 0;
            bottom: 0;
            right: 0;
            height: 12px;
            width: 35%;
        }
    }
</style>
