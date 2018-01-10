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
  - @file ContextualMenuSectionComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="rz-ctx-menu-section">
        <header class="rz-ctx-menu-section-header">{{ label }}</header>
        <p v-for="item in items">
            <ajax-link :title="item.label" :href="item.url" :class="getClass(item)">
                <i :class="'uk-icon-' + item.icon"></i> {{ item.label }}
            </ajax-link>
        </p>
</div>
</template>
<script>
    import AjaxLink from '../AjaxLink.vue'

    export default {
        components: {
            AjaxLink
        },
        props: {
            label: {
                type: String,
                required: true
            },
            items: {
                type: Array,
                required: true
            }
        },
        methods: {
            getClass (item) {
                return {
                    'smaller-text': item.label.length > 17
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    .rz-ctx-menu-section {
        padding: 0 0 0 15px;

        &-header {
            font-size: 11px;
            line-height: 14px;
            margin: 0;
            padding: 7px 0 3px;
            text-transform: capitalize;
            color: #333333;
            font-weight: 700;
        }

        p {
            border-bottom: 1px solid #CCCCCC;
            margin: 0;

            a {
                padding: 7px 13px 4px 13px;
                display: flex;
                flex-flow: row-reverse;
                align-items: center;
                justify-content: space-between;
                min-height: 30px;
                text-decoration: none;
                font-weight: bold;
                font-size: 12px;
                color: #666666;
                border-top: 1px solid #fff;
                background-color: #EBEBEB;
                transition: background ease 0.2s, color ease 0.2s;

                &.smaller-text {
                    font-size: 10px;
                }

                &:hover {
                    background-color: #D2D2D2;
                    color: #000;

                    i {
                        color: #000;
                    }
                }

                i {
                    float: right;
                    line-height: 30px;
                    font-size: 16px;
                    transition: color ease 0.2s;
                    margin-left: 6px;
                }
            }

            &:first-of-type a {
                border-top: 0;
            }
        }
    }
</style>
