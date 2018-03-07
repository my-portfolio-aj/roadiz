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
  - @file TreeLabelComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="tree-label">
        <div class="tree-label-inner">
            <slot></slot>
        </div>
        <div
            class="nodetree-published-at uk-badge tree-label-badge"
            :class="getClass"
            v-if="data.statuses.publishable && data.published_at">
            <i class="uk-icon-clock-o"></i> {{ data.published_at | formatDate('DD/MM/Y') }}
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            data: {
                type: Object,
                required: true
            }
        },
        computed: {
            getClass () {
                return {
                    'in-future': this.publishedInFuture()
                }
            }
        },
        methods: {
            publishedInFuture () {
                if (this.data.published_at) {
                    const current = new Date().getTime()
                    const publishedAt = new Date(this.data.published_at).getTime()

                    if (publishedAt > current) {
                        return true
                    }
                }

                return false
            }
        }
    }
</script>
<style lang="scss" scoped>
    .tree-label {
        display: inline-block;
        margin-right: 6px;

        &,
        &-badge,
        &-inner {
            position: relative;
        }

        &-inner {
            z-index: 2;
            display: inline-block;
        }

        &-badge {
            top: 1px;
            z-index: 1;
            line-height: 1;
            margin-left: -21px;
            border-radius: 20px 0 0 20px;
            padding-left: 22px;
            background-color: #cacaca;
            color: #fff;
            text-shadow: 0 -1px 0 #9a9a9a;
            display: inline-block;
            padding-top: 6px;

            i {
                display: none;
            }

            &.in-future {
                background-color: #ca8776;
                text-shadow: 0 -1px 0 #855143;

                i {
                    display: inline-block;
                }
            }
        }
    }
</style>
