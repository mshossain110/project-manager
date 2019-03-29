<template>
    <VLayout
        row
        class="task-single"
    >
        <VFlex xs12>
            <template v-if="!isLoading">
                <div class="task-details">
                    <div class="task-top">
                        <VBtn
                            color="primary"
                            dark
                        >
                            <VIcon
                                dark
                                small
                            >
                                check_circle
                            </VIcon>
                            Mark Complete
                        </VBtn>

                        <VBtn
                            v-if="lock"
                            flat
                            small
                            icon
                            color="gray lighten-2"
                            @click="changePrivacy"
                        >
                            <VIcon small>
                                lock
                            </VIcon>
                        </VBtn>
                        <VBtn
                            v-if="!lock "
                            flat
                            small
                            icon
                            color="gray lighten-2"
                            @click="changePrivacy"
                        >
                            <VIcon small>
                                lock_open
                            </VIcon>
                        </VBtn>

                        <VMenu
                            ref="datepicker"
                            v-model="datepicker"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            lazy
                            offset-y
                            allow-overflow
                            left
                            transition="scale-transition"
                        >
                            <template v-slot:activator="{ on }">
                                <VBtn
                                    flat
                                    small
                                    icon
                                    color="gray lighten-2"
                                    class="pm-date-picker"
                                    v-on="on"
                                >
                                    <span>
                                        <VIcon small>
                                            date_range
                                        </VIcon>
                                        <span
                                            v-if="task.start_at"
                                            class="green--text"
                                        > {{ momentFormat(task.start_at.date) }} </span>
                                        <span
                                            v-if="task.due_date"
                                            class="red--text"
                                        > - {{ momentFormat(task.due_date.date) }}</span>
                                    </span>
                                </VBtn>
                            </template>
                            <VDatePicker
                                v-model="task.start_at"
                                reactive
                                color="green lighten-1"
                                :min="today"
                                no-title
                            />
                            <VDatePicker
                                v-model="task.due_date"
                                no-title
                                reactive
                                color="red lighten-1"
                                :min="dueMin"
                                @input="closeDatePicker"
                            />
                        </VMenu>
                    </div>
                    <div class="task-title">
                        <h3>{{ task.title }}</h3>
                    </div>
                    <div class="task-description">
                        {{ task.description }}
                    </div>
                </div>
                <Comments
                    :comments="comments"
                    :commentable-type="'task'"
                    :commentable-id="task.id"
                />
            </template>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import moment from 'moment'
import Comments from '@ac/InProject/Comment/Comments.vue'
export default {
    components: {
        Comments
    },
    data () {
        return {
            isLoading: true,
            datepicker: false
        }
    },
    computed: {
        ...mapState('List', ['task']),
        lock () {
            return this.task.private
        },
        today () {
            return moment().toISOString()
        },
        dueMin () {
            return this.task.start_at ? moment(this.task.start_at).toISOString() : moment().toISOString()
        },
        comments () {
            return this.task.comments ? this.task.comments.data : []
        }
    },
    watch: {
        '$route' (route) {
            this.getSelfTask(route.params.id)
        }
    },
    created () {
        this.getSelfTask()
        Bus.$on('Comment:new', this.newComment)
        Bus.$on('Comment:update', this.newComment)
        Bus.$on('Comment:delete', this.deleteComment)
    },
    methods: {
        getSelfTask (id) {
            this.isLoading = true
            let params = {
                id: id || this.$route.params.id,
                include: 'comments'
            }

            this.$store.dispatch('List/getTask', params)
                .then(() => {
                    this.isLoading = false
                })
        },
        newComment (comment) {
            this.task.comments.data = _.unionBy([comment], this.task.comments.data, 'id')
        },
        deleteComment (comment) {
            let i = this.task.comments.data.findIndex(c => c.id === comment.id)
            if (i !== -1) {
                this.task.comments.data.splice(i, 1)
            }
        },
        changePrivacy () {

        },
        closeDatePicker () {
            this.datepicker = false
        }
    }
}
</script>
<style lang="stylus">
.task-single
    padding: 10px;
    border-left: 1px solid #ddd;
    display: flex;
    .task-top
        display flex
        justify-content space-around
        align-items center

</style>
