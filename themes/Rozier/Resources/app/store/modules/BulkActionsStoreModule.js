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
 * @file BulkStoreModule.js
 * @author Adrien Scholaert <adrien@rezo-zero.com>
 */

import {
    BULK_ACTIONS_ADD,
    BULK_ACTIONS_REMOVE
} from '../../types/mutationTypes'

/**
 *  State
 */
const state = {
    items: []
}

/**
 * Getters
 */
const getters = {}

/**
 *  Actions
 */
const actions = {
    bulkActionsAdd ({ commit }, { item }) {
        commit(BULK_ACTIONS_ADD, { item })
    },
    bulkActionsRemove ({ commit }, { item }) {
        commit(BULK_ACTIONS_REMOVE, { item })
    }
}

/**
 *  Mutations
 */
const mutations = {
    [BULK_ACTIONS_ADD] (state, { item }) {
        state.items.push(item)
    },
    [BULK_ACTIONS_REMOVE] (state, { item }) {
        const index = state.items.indexOf(item)

        if (index >= 0) {
            state.items.splice(index, 1)
        }
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
