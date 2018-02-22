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
  - @file TreeItemComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div
        class="nodetree-element rz-nestable-item"
        :class="getNodeTreeClass">
        <div class="nodetree-element-inner rz-nestable-panel" :class="getNodeTreeInnerClass">
            <tree-label-component :data="data">
                <tree-icon-component :data="data" />
            </tree-label-component>
            <tree-link-component :data="data" />
            <tree-nestable-component @change="onTreeNestableChange" v-if="data.children && data.children.length > 0" />
            <tree-contextual-menu-component :data="data" />
        </div>
        <tree-list-component name="sub-tree-list" v-show="isActive" :is-child="true" :data="data.children" @change="onChange" />
    </div>
</template>
<script>
    import TreeLabelComponent from './TreeLabelComponent.vue'
    import TreeIconComponent from './TreeIconComponent.vue'
    import TreeLinkComponent from './TreeLinkComponent.vue'
    import TreeNestableComponent from './TreeNestableComponent.vue'
    import TreeContextualMenuComponent from './TreeContextualMenuComponent.vue'

    export default {
        name: 'tree-item-component',
        components: {
            TreeLabelComponent,
            TreeIconComponent,
            TreeLinkComponent,
            TreeNestableComponent,
            TreeContextualMenuComponent
        },
        props: {
            data: {
                type: Object
            }
        },
        data () {
            return {
                isActive: true
            }
        },
        methods: {
            onChange () {
                this.$emit('change')
            },
            onTreeNestableChange () {
                this.isActive = !this.isActive
            }
        },
        computed: {
            getNodeTreeClass () {
                return [{
                    'has-children rz-parent': this.data.children
                },
                    this.data.type && this.data.type.name ? this.data.type.name.toLowerCase() : null
                ]
            },
            getNodeTreeInnerClass () {
                return {
                    'hidden-node': !this.data.statuses.visible
                }
            }
        }
    }
</script>
<style lang="scss">
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

        .nodetree-element-inner {
            position: relative;
            z-index: 2;
            margin-left: 13px;
            height: 18px;
            padding-top: 4px;
        }

        .rz-nestable-panel {
            &:hover {
                .nodetree-contextualmenu {
                    opacity: 1;
                }
            }

            &.hidden-node > .nodetree-element-name a {
                text-decoration: line-through;
                opacity: 0.8;
            }
        }
    }
</style>
