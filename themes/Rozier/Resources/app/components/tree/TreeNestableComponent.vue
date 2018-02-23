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
  - @file TreeNestableComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="nodetree-minus" :class="{ 'active': isActive }" @click="onClick"></div>
</template>
<script>
    export default {
        data () {
            return {
                isActive: false
            }
        },
        methods: {
            onClick () {
                this.isActive = !this.isActive
                this.$emit('change', this.isActive)
            }
        }
    }
</script>
<style lang="scss" scoped>
    .nodetree-minus {
        position: relative;
        width: 16px;
        height: 17px;
        -webkit-font-smoothing: antialiased;
        cursor: pointer;
        user-select: none;

        &::before,
        &::after {
            position: absolute;
            top: 50%;
            left: 50%;
            display: block;
            content: '';
            height: 1px;
            width: 9px;
            margin-top: -1px;
            margin-left: -5px;
            background-color: #A0A0A0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
        }

        &::after {
            border-bottom: none;
            transform: rotate(90deg);
            transform-origin: 50% 50%;
            display: none;
        }

        &.active::after {
            display: block;
        }
    }
</style>
