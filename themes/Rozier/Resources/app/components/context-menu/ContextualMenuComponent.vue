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
  - @file ContextualMenuComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <nav
        ref="contextMenu"
        @click.stop
        @contextmenu.stop
        :style="ctxStyle"
        class="ctx-menu-container">
        <transition name="fade">
            <div class="ctx-menu-wrapper" v-if="ctxData && ctxVisible">
                <contextual-menu-vertical-component :data="ctxData" />
                <div class="ctx-menu-sections-wrapper">
                    <contextual-menu-section-component v-if="ctxData.actions" :items="ctxData.actions" label="Actions" />
                    <!--<contextual-menu-section-component v-if="ctxData.actions" :items="ctxData.actions" label="Actions" />-->
                </div>
            </div>
        </transition>
    </nav>
</template>
<script>
    import { ContextualMenu } from './ContextualMenuScript'
    export default ContextualMenu
</script>
<style lang="scss" scoped>
    .ctx-menu-container {
        position: fixed;
        padding: 0;
        border: 1px solid #bbb;
        background-color: whitesmoke;
        z-index: 99999;
        width: 220px;
        min-height: 200px;
        box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15);
    }

    .ctx-menu-wrapper {
        display: flex;
    }

    .ctx-menu-sections-wrapper {
        display: flex;
        flex-flow: column;
    }
</style>
