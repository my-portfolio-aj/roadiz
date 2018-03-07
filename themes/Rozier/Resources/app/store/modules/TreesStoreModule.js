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
    TREES_INIT,
    TREES_LOADING,
    TREES_DESTROY
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
    },
    treesGetIsLoading: state => uid => {
        return state.items[uid] ? state.items[uid].isLoading : false
    }
}

/**
 * Actions
 */
const actions = {
    treesInit ({ commit, dispatch }, { url, uid }) {
        commit(TREES_INIT, { uid, url })
        dispatch('treesMakeRequest', { url, uid })
    },
    treesDestroy ({ commit }, { uid }) {
        commit(TREES_DESTROY, { uid })
    },
    treesUpdateList ({ commit }, { data, uid }) {
        commit(TREES_UPDATE_LIST, { data, uid })
    },
    langsUpdated ({ dispatch }) {
        for (let key in state.items) {
            if (state.items.hasOwnProperty(key) && state.items[key].url) {
                dispatch('treesMakeRequest', {
                    url: state.items[key].url,
                    uid: key
                })
            }
        }
    },
    treesMakeRequest ({ commit, rootGetters }, { url, uid }) {
        commit(TREES_LOADING, { isLoading: true, uid })

        const lang = rootGetters.langsGetCurrentLang

        Promise
            .all([Promise.delay(500), NodeTreeApi.getTree(url, { translateId: lang.id })])
            .then(results => {
                commit(TREES_LOADING, { isLoading: false, uid })
                commit(TREES_UPDATE_LIST, { data: results[1], uid })
            })
    }
}

/**
 * Mutations
 */
const mutations = {
    [TREES_INIT] (state, { uid, url }) {
        Vue.set(state.items, uid, {})
        Vue.set(state.items[uid], 'url', url)
        Vue.set(state.items[uid], 'isLoading', false)
    },
    [TREES_UPDATE_LIST] (state, { data, uid }) {
        state.items[uid] = { ...state.items[uid], ...data }
    },
    [TREES_LOADING] (state, { isLoading, uid }) {
        state.items[uid].isLoading = isLoading
    },
    [TREES_DESTROY] (state, { uid }) {
        Vue.delete(state.items, uid)
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
