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
    <div class="timeline-wrapper tree-loading-component">
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

    .tree-loading-component {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        overflow: hidden;
    }

    .timeline-wrapper {
        min-height: 128px;
        padding: 20px 0;

        #main-content-scrollable & {
            background-color: #F0F0F0;
        }
    }

    .timeline-item {
        padding: 0 14px;
        margin: 0;
        max-width: 370px;
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

        #main-content-scrollable & {
            background: linear-gradient(to right, $silver 8%, #bababa 18%, $silver 33%);
            background-size: 800px 104px;
        }
    }
</style>
