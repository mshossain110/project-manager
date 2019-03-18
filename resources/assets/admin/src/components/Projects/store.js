export default {
    namespaced: true,
    state: {
        projects: [],
        projectMeta: {},
        project: {},
        project_users: [],
        categories: [],
        assignees: [],
        isFetchProjects: false,

        projects_view: 'grid_view'
    },
    getters: {
        pagination (state) {
            return state.projectMeta.pagination
        },
        projectUsers (state) {
            return state.project.assignees.data
        }
    },
    mutations: {
        setProjects (state, payload) {
            payload = _.isArray(payload) ? payload : [payload]
            state.projects = _.unionBy(payload, state.projects, 'id')
        },
        setProject (state, payload) {
            state.project = payload
        },
        setProjectMeta (state, payload) {
            state.projectMeta = payload
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
                        commit('setProjects', res.data.data)
                        commit('setProjectMeta', res.data.meta)
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
        getProject ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/projects/${params.id}`, { params })
                    .then((res) => {
                        commit('setProject', res.data.data)
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
                        commit('setProjects', res.data.data)
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
                        commit('setProjects', res.data.data)
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
