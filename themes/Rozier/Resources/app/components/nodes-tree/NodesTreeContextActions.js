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
 * @file NodesTreeContextActions.js
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

import $ from 'jquery'

export default class NodesTreeContextActions {
    constructor () {
        this.$contextualMenus = $('.tree-contextualmenu')
        this.$links = this.$contextualMenus.find('.node-actions a')
        this.$nodeMoveFirstLinks = this.$contextualMenus.find('a.move-node-first-position')
        this.$nodeMoveLastLinks = this.$contextualMenus.find('a.move-node-last-position')

        this.onClick = this.onClick.bind(this)
        this.moveNodeToPosition = this.moveNodeToPosition.bind(this)

        if (this.$links.length) {
            this.bind()
        }
    }

    bind () {
        this.$links.off('click', this.onClick)
        this.$links.on('click', this.onClick)

        this.$nodeMoveFirstLinks.off('click')
        this.$nodeMoveFirstLinks.on('click', (e) => this.moveNodeToPosition('first', e))

        this.$nodeMoveLastLinks.off('click')
        this.$nodeMoveLastLinks.on('click', (e) => this.moveNodeToPosition('last', e))
    }

    onClick (event) {
        event.preventDefault()

        let $link = $(event.currentTarget)
        let $element = $($link.parents('.nodetree-element')[0])
        let nodeId = parseInt($element.data('node-id'))
        let statusName = $link.attr('data-status')
        let statusValue = $link.attr('data-value')
        let action = $link.attr('data-action')

        if (typeof action !== 'undefined') {
            window.Rozier.lazyload.canvasLoader.show()

            if (typeof statusName !== 'undefined' &&
                typeof statusValue !== 'undefined' &&
                !isNaN(statusValue)) {
                // Change node status
                this.changeStatus(nodeId, statusName, parseInt(statusValue))
            } else {
                // Other actions
                if (action === 'duplicate') {
                    this.duplicateNode(nodeId)
                }
            }
        }
    }

    changeStatus (nodeId, statusName, statusValue) {
        if (this.ajaxTimeout) {
            window.clearTimeout(this.ajaxTimeout)
        }

        this.ajaxTimeout = window.setTimeout(() => {
            let postData = {
                '_token': window.Rozier.ajaxToken,
                '_action': 'nodeChangeStatus',
                'nodeId': nodeId,
                'statusName': statusName,
                'statusValue': statusValue
            }

            $.ajax({
                url: window.Rozier.routes.nodesStatusesAjax,
                type: 'post',
                dataType: 'json',
                data: postData
            })
                .done((data) => {
                    window.Rozier.refreshAllNodeTrees()
                    window.UIkit.notify({
                        message: data.responseText,
                        status: data.status,
                        timeout: 3000,
                        pos: 'top-center'
                    })
                })
                .fail(data => {
                    data = JSON.parse(data.responseText)
                    window.UIkit.notify({
                        message: data.responseText,
                        status: data.status,
                        timeout: 3000,
                        pos: 'top-center'
                    })
                })
                .always(() => {
                    window.Rozier.lazyload.canvasLoader.hide()
                })
        }, 100)
    }

    /**
     * Move a node to the position.
     *
     * @param nodeId
     */
    duplicateNode (nodeId) {
        if (this.ajaxTimeout) {
            window.clearTimeout(this.ajaxTimeout)
        }

        this.ajaxTimeout = window.setTimeout(() => {
            let postData = {
                _token: window.Rozier.ajaxToken,
                _action: 'duplicate',
                nodeId: nodeId
            }

            $.ajax({
                url: window.Rozier.routes.nodeAjaxEdit.replace('%nodeId%', nodeId),
                type: 'POST',
                dataType: 'json',
                data: postData
            })
                .done(data => {
                    window.Rozier.refreshAllNodeTrees()
                    window.UIkit.notify({
                        message: data.responseText,
                        status: data.status,
                        timeout: 3000,
                        pos: 'top-center'
                    })
                })
                .fail(data => {
                    console.log(data)
                })
                .always(() => {
                    window.Rozier.lazyload.canvasLoader.hide()
                })
        }, 100)
    }

    /**
     * Move a node to the position.
     *
     * @param {String} position
     * @param {Event} event
     */
    moveNodeToPosition (position, event) {
        window.Rozier.lazyload.canvasLoader.show()

        let element = $($(event.currentTarget).parents('.nodetree-element')[0])
        let nodeId = parseInt(element.data('node-id'))
        let parentNodeId = parseInt(element.parents('ul').first().data('parent-node-id'))
        let postData = {
            _token: window.Rozier.ajaxToken,
            _action: 'updatePosition',
            nodeId: nodeId
        }

        /*
         * Force to first position
         */
        if (typeof position !== 'undefined' && position === 'first') {
            postData.firstPosition = true
        } else if (typeof position !== 'undefined' && position === 'last') {
            postData.lastPosition = true
        }

        /*
         * When dropping to root
         * set parentNodeId to NULL
         */
        if (isNaN(parentNodeId)) {
            parentNodeId = null
        }

        postData.newParent = parentNodeId

        $.ajax({
            url: window.Rozier.routes.nodeAjaxEdit.replace('%nodeId%', nodeId),
            type: 'POST',
            dataType: 'json',
            data: postData
        })
            .done(data => {
                window.Rozier.refreshAllNodeTrees()
                window.UIkit.notify({
                    message: data.responseText,
                    status: data.status,
                    timeout: 3000,
                    pos: 'top-center'
                })
            })
            .always(() => {
                window.Rozier.lazyload.canvasLoader.hide()
            })
    }
}
