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
  - @file TreeIconHidingChildrenComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="rz-tree-icon hiding-children" :class="{ published: statuses.published }">
        <i class="icon-hiding-children" v-bind:style="{ border: `1px solid ${color}` }"></i>
        <span class="icon-hiding-children" v-bind:style="{ border: `1px solid ${color}` }"></span>
        <span class="icon-hiding-children icon-hiding-children-final" v-bind:style="{ border: `1px solid ${color}` }">
            <span v-bind:style="{ backgroundColor: color }"></span>
        </span>
    </div>
</template>
<script>
    export default {
        name: 'tree-icon-hiding-children-component',
        props: {
            color: {
                type: String,
                required: true,
                default: '#999999'
            },
            statuses: {
                type: Object,
                required: true
            }
        }
    }
</script>
<style lang="scss" scoped>
    .icon-hiding-children {
        position: relative;
        content: '';
        color: #999999;
        font-size: 12px;
        border-radius: 3px;
        border: 1px solid #999999;
        width: 10px;
        height: 10px;
        display: inline-block;
        top: 1px;
        transition: border ease 0.2s, background ease 0.2s;
        background-color: #fff;
        transform: rotate(45deg);

        &:nth-child(2),
        &:nth-child(3) {
            margin-left: -12px;
        }

        &.icon-hiding-children-final {
            span {
                position: absolute;
                display: block;
                content: '';
                border-radius: 100%;
                width: 4px;
                height: 4px;
                top: 3px;
                left: 3px;
            }
        }
    }

    .hiding-children {
        margin-left: -3px;

        &:hover {
            .icon-hiding-children {
                background-color: #D3D3D3;
            }
        }

        &.published {
            .icon-hiding-children {
                border-radius: 100%;
            }
        }
    }
</style>
