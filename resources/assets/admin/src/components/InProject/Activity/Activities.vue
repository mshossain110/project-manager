<template>
    <div class="activity">
        activitdsfjkhsdkf sdaf
        {{ activities }}
    </div>
</template>

<script>
export default {
    data () {
        return {
            activities: []
        }
    },
    computed: {

    },
    created () {
        this.getActivities()
    },
    methods: {
        getActivities () {
            let params = {
                project_id: this.$route.params.project_id
            }

            axios.get('/api/activities', { params })
                .then((res) => {
                    this.activities = res.data.data
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
                })
        }
    }
}
</script>
