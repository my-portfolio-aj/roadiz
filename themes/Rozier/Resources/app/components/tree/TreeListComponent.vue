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
  - @file nodeTreeListComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <draggable
        :list="list"
        :options="options"
        :class="{
            'nodetree root-tree rz-nestable': !isChild,
            'rz-nestable-list': isChild
        }"
        @end="onEnd"
        @start="onDragStart"
        class="dragArea">
        <tree-item-component
            v-for="item in list"
            :key="item.id"
            @change="onEnd"
            :data="item" />
    </draggable>
</template>
<script>
    import draggable from 'vuedraggable'
    import TreeItemComponent from './TreeItemComponent.vue'
    import $ from 'jquery'

    export default {
        name: 'tree-list-component',
        components: {
            draggable,
            TreeItemComponent
        },
        props: {
            data: {
                type: Array,
                required: true
            },
            isChild: {
                type: Boolean
            }
        },
        computed: {
            list: {
                get () {
                    return this.data
                }
            },
            options () {
                return {
                    animation: 150,
                    handle: '.rz-handle',
                    filter: '.disabled',
                    sort: true,
                    group: {
                        name: 'node-tree',
                        put: true
                    }
                }
            }
        },
        mounted () {
            this.$body = $('body')
        },
        methods: {
            onEnd () {
                this.$emit('change')
                this.$body.removeClass('on-drag')
            },
            onDragStart () {
                this.$body.addClass('on-drag')
            }
        }
    }
</script>
<style lang="scss" scoped>
    .root-tree > .nodetree-element {
        border-left: 0;
        border-top: 1px solid #BDBDBD;
        padding-top: 9px;
        margin-top: 14px;
        margin-left: 0;

        &:first-of-type {
            border-top: 0;
            padding-top: 0;
            margin-top: 0;
        }

        &:before,
        &:after {
            display: none;
        }
    }

    .dragArea {
        display: block;
        min-height: 17px;
        box-sizing: border-box;
        margin-left: 25px;
        position: relative;
        margin-top: -15px;
        padding-top: 16px;

        body.on-drag & {
            z-index: 10;
        }
    }

    .no-elements::before {
        display: none !important;
    }

    .rz-nestable-list > .rz-nestable-item:last-of-type {
        &:before {
            bottom: 16px;
        }
    }

    .sortable-ghost {
        position: relative;
        background: #aaa !important;
        border: 0;

        &:after {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            height: 100% !important;
            width: 100% !important;
            border: 1px dashed #ddd !important;
        }

        &:before,
        & * {
            opacity: 0 !important;
        }
    }
</style>
