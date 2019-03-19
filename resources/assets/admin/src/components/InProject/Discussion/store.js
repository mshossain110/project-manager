export default {
    namespaced: true,
    state: {
        discussions: [],
        discussionsMeta: {},
        discussion: {}
    },
    getters: {
        pagination (state) {
            return state.discussionsMeta.pagination
        }
    },
    mutations: {
        setDiscussions (state, payload) {
            payload = _.isArray(payload) ? payload : [payload]
            state.discussions = _.unionBy(payload, state.discussions, 'id')
        },
        setDiscussionSingle (state, payload) {
            state.discussion = payload
        },
        setMeta (state, payload) {
            state.discussionsMeta = payload
        },
        deleteDiscussion (state, id) {
            let i = state.discussions.findIndex(l => l.id === id)
            state.discussions.splice(i, 1)
        }
    },
    actions: {
        getDiscussions ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get('/api/discussions', { params })
                    .then((res) => {
                        commit('setDiscussions', res.data.data)
                        commit('setMeta', res.data.meta)
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
        getDiscussion ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/discussions/${params.id}`, { params })
                    .then((res) => {
                        commit('setDiscussionSingle', res.data.data)
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
        addDiscussion ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.post('/api/discussions', params)
                    .then((res) => {
                        commit('setDiscussions', res.data.data)
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
        updateDiscussion ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/discussions/${params.id}`, params)
                    .then((res) => {
                        commit('setDiscussions', res.data.data)
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
        deleteDiscussion ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/discussions/${params.id}`, params)
                    .then((res) => {
                        commit('deleteDiscussion', params.id)
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
