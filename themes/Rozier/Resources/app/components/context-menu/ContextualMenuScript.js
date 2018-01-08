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

export const ContextualMenu = {
    name: 'contextual-menu-component',
    props: {
        id: {
            type: String,
            default: 'default-ctx'
        }
    },
    watch: {
        ctxVisible (newVal, oldVal) {
            if (oldVal === true && newVal === false) {
                this.bodyClickListener.stop((e) => {
                    // console.log('context menu sequence finished', e)
                    // this.locals = {}
                })
            } else {
                this.open()
            }
        },
        ctxEvent (newVal, oldVal) {
            if (oldVal && !newVal) {
                this.bodyClickListener.stop((e) => {})
            } else {
                this.open()
            }
        }
    },
    data () {
        return {
            ctxTop: 0,
            ctxLeft: 0,
            ctxVisible: false,
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
            this.ctxVisible = false
            this.contextualMenuClose()
            this.$emit('ctx-close', this.locals)
        },
        cancel () {
            this.ctxVisible = false
            this.contextualMenuClose()
            this.$emit('ctx-cancel', this.locals)
        },

        /*
         * this function handles some cross-browser compat issues
         * thanks to https://github.com/callmenick/Custom-Context-Menu
         */
        setPositionFromEvent (e) {
            e = e || window.event

            const scrollingElement = document.scrollingElement || document.documentElement

            if (e.pageX || e.pageY) {
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

                if (this.ctxTop > largestHeight) this.ctxTop = largestHeight
                if (this.ctxLeft > largestWidth) this.ctxLeft = largestWidth
            })

            return e
        },

        open (e, data) {
            if (this.ctxVisible) this.ctxVisible = false
            if (!e && this.ctxEvent) e = this.ctxEvent
            this.ctxVisible = true
            if (!data && this.ctxEl) data = this.ctxEl
            this.$emit('ctx-open', this.locals = data || {})
            this.setPositionFromEvent(e)
            this.$el.setAttribute('tab-index', -1)
            this.bodyClickListener.start()
            return this
        }
    },
    computed: {
        ...mapState({
            ctxEvent: state => state.contextMenu.data.event,
            ctxEl: state => state.contextMenu.data.el
        }),
        ctxStyle () {
            return {
                'display': this.ctxVisible ? 'block' : 'none',
                'top': (this.ctxTop || 0) + 'px',
                'left': (this.ctxLeft || 0) + 'px'
            }
        }
    }
}
