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
    NODES_TREE_UPDATE_LIST,
    NODES_TREE_SELECT_NODE
} from '../../types/mutationTypes'

/**
 * Module state
 */
const state = {
    list: [],
    selectedNode: null
}

/**
 * Getters
 */
const getters = {}

/**
 * Actions
 */
const actions = {
    nodesTreeGetAll ({ commit }) {
        NodeTreeApi
            .getNodesTree()
            .then(data => {
                commit(NODES_TREE_UPDATE_LIST, data.items)
            })
    },
    nodesTreeUpdateList ({ commit }, values) {
        commit(NODES_TREE_UPDATE_LIST, values)
    },
    nodesTreeSelectNode ({ commit }, node) {
        commit(NODES_TREE_SELECT_NODE, node)
    }
}

/**
 * Mutations
 */
const mutations = {
    [NODES_TREE_UPDATE_LIST] (state, values) {
        state.list = values
    },
    [NODES_TREE_SELECT_NODE] (state, node) {
        state.selectedNode = node
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
