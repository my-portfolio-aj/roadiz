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
    <div
        v-show="visible || isItem()"
        class="tree-contextualmenu uk-button-dropdown"
        :class="{ 'context-menu-open': isItem() }">
        <div class="tree-contextualmenu-button uk-button uk-button-mini"
             :class="{ 'disabled': isItem() }"
             @click.stop="onClick"
             ref="button">
            <i class="uk-icon-caret-down"></i>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'

    export default {
        name: 'tree-contextual-menu-component',
        props: {
            data: {
                type: Object
            },
            visible: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            ...mapActions([
                'contextualMenuOpen',
                'contextualMenuClose',
                'contextualMenuReset'
            ]),
            onClick (e) {
                this.contextualMenuOpen({
                    event: e,
                    data: this.data,
                    el: this.$refs.button
                })
            },
            isItem () {
                return this.ctxMenuVisible && this.ctxMenuData && this.ctxMenuData.id === this.data.id
            }
        },
        computed: {
            ...mapState({
                ctxMenuVisible: state => state.contextMenu.isOpen,
                ctxMenuData: state => state.contextMenu.obj.data
            })
        }
    }
</script>
<style lang="scss" scoped>
    .tree-contextualmenu {
        &:hover {
            opacity: 1;
        }

        .uk-icon-caret-down {
            font-size: 10px;
            margin: 0 0 0 1px;
        }
    }

    .context-menu-open {
        opacity: 1;
    }

    .disabled {
        pointer-events: none;
    }
</style>
