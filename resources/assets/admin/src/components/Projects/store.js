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
            state.projects.concat(payload)
        },
        setPagination (state, payload) {
            state.pagination = payload
        }
    },
    actions: {
        getProject ({ commit }, params) {
            return new Promise((resolve, reject) => {
                axios.get('/api/project', { params })
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
        }
    }
}
