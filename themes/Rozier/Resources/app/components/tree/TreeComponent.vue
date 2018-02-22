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
  - @file NodeTree.vue
  - @author Adrien Scholaert <adrien@rezo-zero.com>
  -->

<template>
    <div class="tree-component-wrapper">
        <transition name="fade" key="tree-list">
            <div class="tree-component" v-if="treesGetTreeItemsById(uid) && !treesGetIsLoading(uid)">
                <tree-list-component
                    v-if="treesGetTreeItemsById(uid).length > 0"
                    :data="treesGetTreeItemsById(uid)"
                    :is-child="false"
                    @change="onChange" />
            </div>
            <tree-loading-component v-else key="tree-loading" />
        </transition>
    </div>
</template>
<script>
    import uniqid from 'uniqid'
    import { mapActions, mapGetters } from 'vuex'

    // Components
    import TreeListComponent from './TreeListComponent.vue'
    import TreeLoadingComponent from './TreeLoadingComponent.vue'

    export default {
        name: 'tree-component',
        props: {
            url: {
                type: String
            }
        },
        computed: {
            ...mapGetters([
                'treesGetTreeItemsById',
                'treesGetIsLoading',
                'treesGetTreeById'
            ])
        },
        components: {
            TreeListComponent,
            TreeLoadingComponent
        },
        created () {
            // Create a uniqued ID to identify the list
            this.uid = uniqid()
        },
        beforeMount () {
            // Init a new Tree
            this.treesInit({ url: this.url, uid: this.uid })
        },
        methods: {
            ...mapActions([
                'treesInit',
                'treesUpdateList'
            ]),
            onChange () {
                this.treesUpdateList({ data: this.treesGetTreeById(this.uid), uid: this.uid })
            }
        }
    }
</script>
<style lang="scss" scoped>
    .tree-component-wrapper {
        position: relative;
    }

    .tree-component {
        position: relative;

        #main-content-scrollable & {
            background: #F0F0F0;
        }
    }
</style>
