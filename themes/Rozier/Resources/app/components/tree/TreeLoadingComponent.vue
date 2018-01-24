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
  - @file TreeLoadingComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="timeline-wrapper">
        <div class="timeline-item">
            <div class="animated-background">
                <tree-loading-item-component>
                    <tree-loading-item-component />
                    <tree-loading-item-component />
                    <tree-loading-item-component />
                </tree-loading-item-component>
                <tree-loading-item-component>
                    <tree-loading-item-component>
                        <tree-loading-item-component />
                        <tree-loading-item-component />
                    </tree-loading-item-component>
                </tree-loading-item-component>
            </div>
        </div>
    </div>
</template>
<script>
    import TreeLoadingItemComponent from './TreeLoadingItemComponent.vue'

    export default {
        components: {
            TreeLoadingItemComponent
        }
    }
</script>
<style lang="scss" scoped>
    @import '../../scss/variables';

    .timeline-item {
        padding: 0 23px;
        margin: 0 auto;
    }

    @keyframes placeHolderShimmer{
        0%{
            background-position: -468px 0
        }
        100%{
            background-position: 468px 0
        }
    }

    .animated-background {
        animation-duration: 1s;
        animation-fill-mode: forwards;
        animation-iteration-count: infinite;
        animation-name: placeHolderShimmer;
        animation-timing-function: linear;
        background: #f6f7f8;
        background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
        background-size: 800px 104px;
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
        margin-left: 30px;

        &::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: -30px;
            width: 30px;
            background-color: $silver;
        }
    }

    .background-masker {
        background: $silver;
        position: absolute;

        &.rounded {
            height: 12px;
            width: 12px;
            left: 0;
            top: 0;
            background: radial-gradient(circle closest-side at center, rgba(0,0,0,0) 100%, $silver);
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
