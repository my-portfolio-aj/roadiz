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
 * @file index.js
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

import Vuex from 'vuex'
import {
    KEYBOARD_EVENT_ESCAPE,
    LOGIN_CHECK_DISCONNECTED
} from '../types/mutationTypes'

// Modules
import nodesSourceSearch from './modules/NodesSourceSearchStoreModule'
import explorer from './modules/ExplorerStoreModule'
import drawers from './modules/DrawersStoreModule'
import filterExplorer from './modules/FilterExplorerStoreModule'
import tags from './modules/TagsStoreModule'
import documentPreview from './modules/DocumentPreviewStoreModule'
import blanchetteEditor from './modules/BlanchetteEditorStoreModule'

import * as NodeTreeApi from '../api/NodeTreeApi'

const UPDATE_LIST = 'updateList'

export default new Vuex.Store({
    modules: {
        nodesSourceSearch,
        explorer,
        filterExplorer,
        drawers,
        tags,
        documentPreview,
        blanchetteEditor
    },
    state: {
        translations: window.RozierRoot.messages,
        connected: true,
        list: []
        // list: [{
        //     text: 'main-menu',
        //     id: 0,
        //     children: [{
        //         text: 'Lorem ipsum 1',
        //         id: 1,
        //         children: [{
        //             text: 'Emma watson',
        //             id: 3,
        //             children: []
        //         }, {
        //             text: 'Paul jackson',
        //             id: 4,
        //             children: []
        //         }]
        //     }, {
        //         text: 'Lorem ipsum 2',
        //         id: 2,
        //         children: []
        //     }, {
        //         text: 'Lorem ipsum 1',
        //         id: 5,
        //         children: [{
        //             text: 'Emma watson',
        //             id: 6,
        //             children: []
        //         }, {
        //             text: 'Paul jackson',
        //             id: 7,
        //             children: []
        //         }]
        //     }, {
        //         text: 'Lorem ipsum 2',
        //         id: 8,
        //         children: []
        //     }]
        // }]
    },
    mutations: {
        [LOGIN_CHECK_DISCONNECTED] (state) {
            state.connected = false
        },
        [UPDATE_LIST] (state, values) {
            state.list = values
        }
    },
    actions: {
        escape ({ commit }) {
            commit(KEYBOARD_EVENT_ESCAPE)
        },
        getNodesTree ({ commit }) {
            NodeTreeApi
                .getNodesTree()
                .then(data => {
                    commit(UPDATE_LIST, data.items)
                })
        }
    }
})
