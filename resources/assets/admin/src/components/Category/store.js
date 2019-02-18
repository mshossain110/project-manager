
export default {
    namespaced: true,
    state: {
        categories: []
    },
    getters: {
        categories (state) {
            return state.categories
        }

    },
    mutations: {
        setCategories (state, payload) {
            state.categories = payload
        },
        addCategory (state, payload) {
            state.categories.splice(0, 0, payload)
        },
        updateCategory (state, payload) {
            const i = state.categories.findIndex(u => u.id === payload.id)
            if (i !== -1) {
                state.categories[i] = payload
            }
        },
        deleteCategory (state, id) {
            const i = state.categories.findIndex(u => u.id === id)
            if (i !== -1) {
                state.categories.splice(i, 1)
            }
        }
    },
    actions: {
        getCategories ({ commit, state }, params) {
            return new Promise((resolve, reject) => {
                if (state.categories.length) {
                    resolve(state.categories)
                    return
                }
                axios.get('/api/category', { params })
                    .then((res) => {
                        commit('setCategories', res.data.data)
                        resolve(res.data)
                    })
                    .catch((error) => {
                        commit('setSnackbar',
                            {
                                message: error.response.data.message,
                                status: error.response.status,
                                color: 'error',
                                show: true
                            },
                            { root: true })
                        reject(error.response)
                    })
            })
        },
        addCategory ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.post('/api/category', params)
                    .then((res) => {
                        commit('addCategory', res.data.data)
                        commit('setSnackbar',
                            {
                                message: res.data.message,
                                status: res.status,
                                color: 'success',
                                show: true
                            },
                            { root: true })
                        resolve(res.data)
                    })
                    .catch((error) => {
                        commit('setError', error.response.data)
                        reject(error.response)
                    })
            })
        },
        updateCategory ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/category/${params.id}`, params)
                    .then((res) => {
                        commit('updateCategory', res.data.data)
                        commit('setSnackbar',
                            {
                                message: res.data.message,
                                status: res.status,
                                color: 'success',
                                show: true
                            },
                            { root: true })
                        resolve(res.data)
                    })
                    .catch((error) => {
                        commit('setSnackbar',
                            {
                                message: error.response.data.message,
                                status: error.response.status,
                                color: 'error',
                                show: true
                            },
                            { root: true })
                        reject(error.response)
                    })
            })
        },
        deleteCategory ({ commit }, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/category/${id}`)
                    .then((res) => {
                        commit('deleteCategory', id)
                        commit('setSnackbar',
                            {
                                message: res.data.message,
                                status: res.status,
                                color: 'success',
                                show: true
                            },
                            { root: true })
                        resolve(true)
                    })
                    .catch((error) => {
                        commit('setSnackbar',
                            {
                                message: error.response.data.message,
                                status: error.response.status,
                                color: 'error',
                                show: true
                            },
                            { root: true })
                        reject(error.response)
                    })
            })
        }

    }
}
