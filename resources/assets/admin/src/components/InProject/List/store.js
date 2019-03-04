export default {
    namespaced: true,
    state: {
        lists: [],
        listMeta: {},
        list: {},
        task: {}
    },
    getters: {
        pagination (state) {
            return state.listMeta.pagination
        }
    },
    mutations: {
        setLists (state, payload) {
            payload = _.isArray(payload) ? payload : [payload]
            state.lists = _.unionBy(payload, state.lists, 'id')
        },
        setListSingle (state, payload) {
            state.list = payload
        },
        setlistMeta (state, payload) {
            state.listPagination = payload
        },
        deleteList (state, id) {
            let i = state.lists.findIndex(l => l.id === id)
            state.list.splice(i, 1)
        }
    },
    actions: {
        getLists ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get('/api/lists', { params })
                    .then((res) => {
                        commit('setLists', res.data.data)
                        commit('setlistMeta', res.data.meta)
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
        getList ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/lists/${params.id}`, { params })
                    .then((res) => {
                        commit('setListSingle', res.data.data)
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
        addList ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.post('/api/lists', params)
                    .then((res) => {
                        commit('setLists', res.data.data)
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
        updateList ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/lists/${params.id}`, params)
                    .then((res) => {
                        commit('setLists', res.data.data)
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
        deleteList ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/lists/${params.id}`, params)
                    .then((res) => {
                        commit('deleteList', params.id)
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
        }
    }
}
