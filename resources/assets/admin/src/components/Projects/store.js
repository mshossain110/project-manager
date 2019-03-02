export default {
    namespaced: true,
    state: {
        projects: [],
        pagination: {},
        project_users: [],
        categories: [],
        assignees: [],
        isFetchProjects: false,

        projects_view: 'grid_view'
    },
    getters: {

    },
    mutations: {
        setPorjects (state, payload) {
            state.projects = _.unionBy(state.projects, payload, 'id')
        },
        setPagination (state, payload) {
            state.pagination = payload
        },
        addPorject (state, payload) {
            state.projects = _.unionBy(state.projects, [payload], 'id')
        },
        deletePorject (state, id) {
            let i = state.projects.findIndex(p => p.id === id)
            state.projects.splice(i, 1)
        }
    },
    actions: {
        getProjects ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get('/api/projects', { params })
                    .then((res) => {
                        commit('setPorjects', res.data.data)
                        commit('setPagination', res.data.meta.pagination)
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
        addProject ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.post('/api/projects', params)
                    .then((res) => {
                        commit('addPorject', res.data.data)
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
        updateProject ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/projects/${params.id}`, params)
                    .then((res) => {
                        commit('addPorject', res.data.data)
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
        deleteProject ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.delete(`/api/projects/${params.id}`, params)
                    .then((res) => {
                        commit('deletePorject', params.id)
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
