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
    <div>
        <transition name="fade">
            <tree-list-component
                v-if="treesGetListItems(uid)"
                :data="treesGetListItems(uid)"
                :is-child="false"
                @change="onChange" />
        </transition>
    </div>
</template>
<script>
    import uniqid from 'uniqid'
    import TreeListComponent from './TreeListComponent.vue'
    import { mapActions, mapGetters } from 'vuex'

    const uid = uniqid()

    export default {
        name: 'tree-component',
        props: {
            url: {
                type: String
            }
        },
        data () {
            return {
                uid
            }
        },
        computed: {
            ...mapGetters([
                'treesGetListItems',
                'treesGetList'
            ])
        },
        components: {
            TreeListComponent
        },
        beforeMount () {
            this.treesInit({ url: this.url, uid: this.uid })
        },
        methods: {
            ...mapActions([
                'treesInit',
                'treesUpdateList'
            ]),
            onChange () {
                this.treesUpdateList({ data: this.treesGetList(this.uid), uid: this.uid })
            }
        }
    }
</script>
