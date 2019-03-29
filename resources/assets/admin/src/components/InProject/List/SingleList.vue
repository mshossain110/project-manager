<template>
    <VLayout
        row
        class="list-single"
    >
        <VFlex xs12>
            <template v-if="!isLoading">
                <div class="list-details">
                    <div class="list-title">
                        <h3>{{ list.title }}</h3>
                    </div>
                    <div class="list-description">
                        {{ list.description }}
                    </div>
                </div>
                <Comments
                    :comments="comments"
                    commentable-type="task_list"
                    :commentable-id="list.id"
                />
            </template>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import Comments from '@ac/InProject/Comment/Comments.vue'
export default {
    components: {
        Comments
    },
    data () {
        return {
            isLoading: true
        }
    },
    computed: {
        ...mapState('List', ['list']),
        comments () {
            return this.list.comments ? this.list.comments.data : []
        }
    },
    created () {
        this.getSelfList()
        Bus.$on('Comment:new', this.newComment)
        Bus.$on('Comment:update', this.newComment)
        Bus.$on('Comment:delete', this.deleteComment)
    },
    methods: {
        getSelfList (id) {
            this.isLoading = true
            let params = {
                id: id || this.$route.params.id,
                include: 'comments'
            }

            this.$store.dispatch('List/getList', params)
                .then(() => {
                    this.isLoading = false
                })
        },
        newComment (comment) {
            this.list.comments.data = _.unionBy([comment], this.list.comments.data, 'id')
        },
        deleteComment (comment) {
            let i = this.list.comments.data.findIndex(c => c.id === comment.id)
            if (i !== -1) {
                this.list.comments.data.splice(i, 1)
            }
        }
    }
}
</script>
<style lang="stylus">
.list-single
    padding: 10px;
    border-left: 1px solid #ddd;
</style>
