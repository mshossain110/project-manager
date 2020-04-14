export default {
    namespaced: true,
    state: {
        milestones: [],
        milestonesMeta: {},
        milestone: {}
    },
    getters: {
        pagination (state) {
            return state.milestonesMeta.pagination
        }
    },
    mutations: {
        setMilestone (state, payload) {
            payload = _.isArray(payload) ? payload : [payload]
            state.milestones = _.unionBy(payload, state.milestones, 'id')
        },
        setMilestoneSingle (state, payload) {
            state.milestone = payload
        },
        setMeta (state, payload) {
            state.milestonesMeta = payload
        },
        deleteMilestone (state, id) {
            let i = state.milestones.findIndex(l => l.id === id)
            state.milestones.splice(i, 1)
        },
        newComment (state, payload) {
            let i = state.milestones.findIndex(o => o.id === payload.commentable_id)
            if (i !== -1) {
                if (typeof state.milestones[i].comments === 'undefined') {
                    state.milestones[i].comments = {
                        data: [payload]
                    }
                } else {
                    state.milestones[i].comments.data.push(payload)
                    state.milestones[i].comments.data = _.unionBy(state.milestones[i].comments.data, [], 'id')
                }
            }
        },
        deleteComment (state, payload) {
            let i = state.milestones.findIndex(o => o.id === payload.commentable_id)
            if (i !== -1) {
                let j = state.milestones[i].comments.data.findIndex(a => a.id === payload.id)
                state.milestones[i].comments.data.splice(j, 1)
            }
        }
    },
    actions: {
        getMilestones ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get('/api/milestones', { params })
                    .then((res) => {
                        commit('setMilestone', res.data.data)
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
        getMilestone ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/milestones/${params.id}`, { params })
                    .then((res) => {
                        commit('setMilestoneSingle', res.data.data)
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
        addMilestone ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.post('/api/milestones', params)
                    .then((res) => {
                        commit('setMilestone', res.data.data)
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
        updateMilestone ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/milestones/${params.id}`, params)
                    .then((res) => {
                        commit('setMilestone', res.data.data)
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
        deleteMilestone ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/milestones/${params.id}`, params)
                    .then((res) => {
                        commit('deleteMilestone', params.id)
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
