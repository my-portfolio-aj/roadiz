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
  - @file NodeTreeItemComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div
        class="page nodetree-element rz-nestable-item"
        :class="{ 'has-children rz-parent': data.children.length > 0 }">
        <div class="nodetree-element-inner rz-nestable-panel">
            <node-tree-icon-component />
            <node-tree-link-component :text="data.text" />
            <node-tree-contextual-menu-component />
        </div>
        <node-tree-list-component v-if="data.children" name="sub-tree-list" :is-child="true" :data="data.children" @change="onChange" />
    </div>
</template>
<script>
    import NodeTreeIconComponent from './NodeTreeIconComponent.vue'
    import NodeTreeLinkComponent from './NodeTreeLinkComponent.vue'
    import NodeTreeContextualMenuComponent from './NodeTreeContextualMenuComponent.vue'

    export default {
        name: 'node-tree-item-component',
        components: {
            NodeTreeIconComponent,
            NodeTreeLinkComponent,
            NodeTreeContextualMenuComponent
        },
        props: {
            data: {
                type: Object
            }
        },
        methods: {
            onChange () {
                this.$emit('change')
            }
        }
    }
</script>
<style lang="scss" scoped>
    .rz-nestable-item {
        position: relative;
        margin-left: -6px;

        &:before {
            content: '';
            position: absolute;
            top: -3px;
            left: 0;
            bottom: 0;
            width: 1px;
            background-color: #aaa;
        }

        &:last-of-type {
            border-left: none;

            &:before {
                content: '';
                position: absolute;
                top: -3px;
                left: 0;
                height: 17px;
                width: 1px;
                background-color: #aaa;
            }
        }

        &:after {
            content: '';
            position: absolute;
            top: 13px;
            left: 0;
            height: 1px;
            width: 13px;
            background-color: #aaa;
        }
    }

    .nodetree-element-inner {
        position: relative;
        z-index: 2;
        margin-left: 13px;
        height: 18px;
        padding-top: 4px;

        &:hover {
            .nodetree-contextualmenu {
                opacity: 1;
            }
        }
    }
</style>
