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
  - @file NodeTreeIconComponent.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="rz-handle">
        <tree-icon-home-component :color="getColor" v-if="data.statuses.home" />
        <tree-icon-hiding-children-component :color="getColor" :statuses="data.statuses" v-else-if="data.statuses.hiding_children" />
        <tree-icon-unpublished-component :color="getColor" v-else-if="!data.statuses.published" />
        <tree-icon-normal-component :color="getColor" v-else />
    </div>
</template>
<script>
    import TreeIconUnpublishedComponent from '../tree-icon/TreeIconUnpublishedComponent.vue'
    import TreeIconNormalComponent from '../tree-icon/TreeIconNormalComponent.vue'
    import TreeIconHomeComponent from '../tree-icon/TreeIconHomeComponent.vue'
    import TreeIconHidingChildrenComponent from '../tree-icon/TreeIconHidingChildrenComponent.vue'

    export default {
        name: 'tree-icon-component',
        components: {
            TreeIconUnpublishedComponent,
            TreeIconHidingChildrenComponent,
            TreeIconHomeComponent,
            TreeIconNormalComponent
        },
        props: {
            data: {
                type: Object,
                required: true
            }
        },
        computed: {
            getColor () {
                if (this.data && this.data.type && this.data.type.color) {
                    return this.data.type.color
                }

                return '#000'
            }
        }
    }
</script>
<style lang="scss">
    .rz-handle {
        position: relative;
        display: inline-block;
        touch-action: none;

        &:hover {
            cursor: move;
        }
    }
</style>
