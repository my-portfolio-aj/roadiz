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
  - @file NodeTreeContextualMenuComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="tree-contextualmenu nodetree-contextualmenu uk-button-dropdown" :class="{ 'context-menu-open': isHover }">
        <div class="tree-contextualmenu-button uk-button uk-button-mini" v-if="isHover" @click="onClick">
            <i class="uk-icon-caret-down"></i>
        </div>
    </div>
</template>
<script>
    import { mapActions } from 'vuex'

    export default {
        name: 'node-tree-contextual-menu-component',
        data () {
            return {
                isHover: true,
                selected: false
            }
        },
        props: {
            data: {
                type: Object
            }
        },
        methods: {
            ...mapActions([
                'contextualMenuToggle'
            ]),
            onClick (e) {
                this.contextualMenuToggle({ event: e, el: this.data })
            }
        }
    }
</script>
<style lang="scss" scoped>
    .nodetree-contextualmenu {
        position: absolute;
        right: 22px;
        top: 3px;
        opacity: 1;
    }

    .context-menu-open {
        z-index: 22;
    }
</style>
