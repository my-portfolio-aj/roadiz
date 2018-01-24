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
 * @file NodesTreeStoreModule.js
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

import * as NodeTreeApi from '../../api/NodeTreeApi'
import {
    TREES_UPDATE_LIST,
    TREES_INIT
} from '../../types/mutationTypes'
import Vue from 'vue'
import Promise from 'bluebird'

/**
 * Module state
 */
const state = {
    items: {}
}

/**
 * Getters
 */
const getters = {
    treesGetTreeItemsById: state => uid => {
        return state.items[uid] && state.items[uid].items ? state.items[uid].items : null
    },
    treesGetTreeById: state => uid => {
        return state.items[uid] ? state.items[uid] : null
    }
}

/**
 * Actions
 */
const actions = {
    treesInit ({ commit }, { url, uid }) {
        commit(TREES_INIT, uid)

        Promise
            .all([Promise.delay(1500), NodeTreeApi.getTree(url)])
            .then(results => commit(TREES_UPDATE_LIST, { data: results[1], uid }))
    },
    treesUpdateList ({ commit }, { data, uid }) {
        commit(TREES_UPDATE_LIST, { data, uid })
    }
}

/**
 * Mutations
 */
const mutations = {
    [TREES_INIT] (state, uid) {
        Vue.set(state.items, uid)
    },
    [TREES_UPDATE_LIST] (state, { data, uid }) {
        state.items[uid] = data
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
