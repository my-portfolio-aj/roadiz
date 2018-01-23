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
  - @file ContextualMenuVerticalComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="rz-context-menu-vertical" :style="style">
        <i class="uk-icon-rz-lock" v-if="data.statuses && data.statuses.locked"></i>
        <i class="uk-icon-eye-slash" v-if="data.statuses && !data.statuses.visible"></i>
        <div class="type" v-if="data.type && data.type.name">{{ data.type.name }}</div>
    </div>
</template>
<script>
    export default {
        props: {
            data: {
                type: Object
            }
        },
        computed: {
            style () {
                return {
                    'background-color': this.data.type.color
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    .rz-context-menu-vertical {
        position: relative;
        background-color: #000;
        width: 26px;
        min-width: 26px;

        .type {
            position: absolute;
            bottom: -8px;
            left: 7px;
            transform-origin: 0 0;
            transform: rotate(-90deg);
            text-transform: uppercase;
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }

        i {
            color: rgba(255, 255, 255, 0.75);
            margin: 10px 5px;
            display: block;
            width: 16px;
            font-size: 16px;
        }
    }
</style>
