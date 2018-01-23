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
  - @file TreeIconUnpublishedComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="rz-tree-icon unpublish" v-bind:style="{ border: `1px solid ${color}` }"></div>
</template>
<script>
    export default {
        name: 'tree-icon-unpublish-component',
        props: {
            color: {
                type: String,
                required: true
            }
        }
    }
</script>
<style lang="scss" scoped>
    .unpublish {
        width: 9px;
        height: 9px;
        border-radius: 2px;
        transform: rotate(45deg);
        top: 0;
        left: 1px;
        position: relative;
        content: '';
        color: #999999;
        font-size: 12px;
        border: 1px solid #999999;
        display: inline-block;
        transition: border ease 0.2s, background ease 0.2s;
        background-color: #fff;
        margin-right: 1px;

        &:hover {
            background-color: #D3D3D3;
        }
    }
</style>
