/*
 * Copyright (c) 2017. Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name of the ROADIZ shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
 *
 * @file ContextualMenuScript.js
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

import { createBodyClickListener } from '../../utils'
import { mapState, mapActions } from 'vuex'
import ContextualMenuVerticalComponent from './ContextualMenuVerticalComponent.vue'
import ContextualMenuSectionComponent from './ContextualMenuSectionComponent.vue'
import Translator from 'bazinga-translator'

export const ContextualMenu = {
    name: 'contextual-menu-component',
    components: {
        ContextualMenuVerticalComponent,
        ContextualMenuSectionComponent
    },
    props: {
        id: {
            type: String,
            default: 'default-ctx'
        }
    },
    watch: {
        ctxVisible (newVal) {
            if (newVal) {
                this.open()
            } else {
                this.bodyClickListener.stop((e) => {})
            }
        }
    },
    data () {
        return {
            ctxTop: 0,
            ctxLeft: 0,
            trans: {
                statuses: Translator.trans('statuses'),
                actions: Translator.trans('actions')
            },
            bodyClickListener: createBodyClickListener(
                (e) => {
                    let isOpen = !!this.ctxVisible
                    let outsideClick = isOpen && !this.$el.contains(e.target)

                    if (outsideClick) {
                        if (e.which !== 1) {
                            e.preventDefault()
                            e.stopPropagation()
                            return false
                        } else {
                            this.cancel()
                        }
                    } else {
                        this.close()
                    }
                }
            )
        }
    },
    methods: {
        ...mapActions([
            'contextualMenuClose'
        ]),
        close () {
            this.contextualMenuClose()
        },
        cancel () {
            this.contextualMenuClose()
        },
        setPositionFromEvent (e) {
            e = this.ctxEvent || window.event

            let offsetX = 0
            let offsetY = 0

            const scrollingElement = document.scrollingElement || document.documentElement

            if (this.ctxEl) {
                const viewportOffset = this.ctxEl.getBoundingClientRect()
                this.ctxTop = viewportOffset.top
                this.ctxLeft = viewportOffset.left

                offsetX = this.ctxEl.clientWidth
                offsetY = this.ctxEl.clientHeight
            } else if (e.pageX || e.pageY) {
                this.ctxLeft = e.pageX
                this.ctxTop = e.pageY - scrollingElement.scrollTop
            } else if (e.clientX || e.clientY) {
                this.ctxLeft = e.clientX + scrollingElement.scrollLeft
                this.ctxTop = e.clientY + scrollingElement.scrollTop
            }

            this.$nextTick(() => {
                const menu = this.$el
                const minHeight = (menu.style.minHeight || menu.style.height).replace('px', '') || 32
                const minWidth = (menu.style.minWidth || menu.style.width).replace('px', '') || 32
                const scrollHeight = menu.scrollHeight || minHeight
                const scrollWidth = menu.scrollWidth || minWidth

                const largestHeight = window.innerHeight - scrollHeight - 25
                const largestWidth = window.innerWidth - scrollWidth - 25

                this.ctxLeft -= menu.clientWidth - offsetX
                this.ctxTop += offsetY

                if (this.ctxTop > largestHeight) this.ctxTop = largestHeight
                if (this.ctxLeft > largestWidth) this.ctxLeft = largestWidth
            })

            return e
        },

        open () {
            this.setPositionFromEvent()
            this.$el.setAttribute('tab-index', -1)
            this.bodyClickListener.start()
            return this
        }
    },
    computed: {
        ...mapState({
            ctxVisible: state => state.contextMenu.isOpen,
            ctxEvent: state => state.contextMenu.obj.event,
            ctxEl: state => state.contextMenu.obj.el,
            ctxData: state => state.contextMenu.obj.data
        }),
        ctxStyle () {
            return {
                'display': this.ctxVisible ? 'flex' : 'none',
                'top': (this.ctxTop || 0) + 'px',
                'left': (this.ctxLeft || 0) + 'px'
            }
        }
    }
}
