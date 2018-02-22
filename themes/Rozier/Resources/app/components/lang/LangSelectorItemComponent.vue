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
  - @file LangSelectorItemComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <li class="lang-selector-item"
        @click="onClick"
        :class="{ active: lang.selected }">
        {{ lang.locale }}
    </li>
</template>
<script>
    // import { mapActions } from 'vuex'

    export default {
        props: {
            lang: {
                type: Object,
                required: true
            }
        },
        methods: {
            onClick () {
                this.$store.dispatch('langsSelected', this.lang)
            }
        }
    }
</script>
<style lang="scss" scoped>
    .lang-selector-item {
        position: relative;
        font-size: 11px;
        color: #555;
        display: inline-block;
        border-left: 1px solid #BBB;
        padding: 2px 10px 3px 10px;
        box-sizing: border-box;
        text-decoration: none;
        margin: 0;
        outline: none;
        cursor: pointer;
        text-transform: capitalize;

        &:first-child {
            border-left: 0 none;
        }

        &::after {
            position: absolute;
            display: block;
            content: '';
            right: 8px;
            bottom: 1px;
            left: 8px;
            height: 2px;
            background-color: transparent;
            transition: 0.4s background-color cubic-bezier(0.19, 1, 0.22, 1);
        }

        &.active::after,
        &:hover::after {
            background-color: #777;
        }
    }
</style>
