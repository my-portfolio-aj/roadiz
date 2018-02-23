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
  - @file TreeBulkSelection.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <input
        type="checkbox"
        class="checkbox"
        v-show="visible || isChecked"
        @click="onClick">
</template>
<script>
    import { mapActions } from 'vuex'

    export default {
        props: {
            data: {
                type: Object
            },
            visible: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                isChecked: false
            }
        },
        beforeDestroy () {
            if (this.isChecked) {
                this.bulkActionsRemove({ item: this.data })
            }
        },
        methods: {
            ...mapActions([
                'bulkActionsAdd',
                'bulkActionsRemove'
            ]),
            onClick () {
                this.isChecked = !this.isChecked

                if (this.isChecked) {
                    this.bulkActionsAdd({ item: this.data })
                } else {
                    this.bulkActionsRemove({ item: this.data })
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    .checkbox {
        width: 14px;
        height: 14px;
        line-height: 12px;
        border: 1px solid #aaa;
        overflow: hidden;
        vertical-align: middle;
        -webkit-appearance: none;
        outline: 0;
        background: #fff;

        &:checked:before {
            content: "\F00C";
            font-family: 'FontAwesome';
            font-size: 12px;
            -webkit-font-smoothing: antialiased;
            text-align: center;
            line-height: 12px;
            color: #00ab84;
        }
    }
</style>
