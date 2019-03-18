<template>
    <div class="task">
        <div class="task-status">
            <VCheckbox
                :value="isComplete"
                @change="changeTaskStatus()"
            />
        </div>
        <div class="task-title">
            {{ task.title }}
        </div>
        <div class="action">
            <div class="task-date">
                <span v-if="task.start_at">{{ momentFormat(task.start_at.date) }}</span>
                <span v-if="task.due_date">{{ momentFormat(task.due_date.date) }}</span>
            </div>
            <Assignee :assignees="task.assignees.data" />
            <div class="private">
                <VIcon
                    v-if="task.private"
                    small
                >
                    lock
                </VIcon>
                <VIcon
                    v-if="!task.private"
                    small
                >
                    lock_open
                </VIcon>
            </div>
        </div>
    </div>
</template>

<script>
import Assignee from '@ac/common/Assignee.vue'
import moment from 'moment'
export default {
    components: {
        Assignee
    },
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            taskstatus: false
        }
    },
    computed: {
        isComplete () {
            return this.task.status === 'complete'
        }
    },
    methods: {
        momentFormat (date) {
            if (date) {
                return moment(date).format('DD MMM')
            }
        },
        changeTaskStatus () {
            console.log(this.task.status)
            this.$store.dispatch('List/changeTaskStatus', {
                id: this.task.id,
                status: this.task.status === 'incomplete' ? 1 : 0
            })
        }
    }
}
</script>

<style lang="stylus">
.task
    display flex
    justify-content space-between
    align-items center
    padding 5px 20px
    border-top: 1px solid #FFF
    border-bottom: 1px solid #ddd
    &:hover
        background: #fcfcfc
        border-top: 1px solid #ddd
        border-bottom: 1px solid #ddd
    .task-status
        .v-input--selection-controls
            margin-top: 0px;
            padding-top: 0px;
        .v-input--selection-controls__ripple
        .v-messages
            display: none
        .v-input__slot
            margin-bottom: 0 !important
    .task-title
        flex-grow: 1;
    .action
        display: flex
        justify-content: flex-end
        align-items: center
        &> div
            margin-right: 5px
        .task-date
            span
                &:first-child
                    color: #1c7302
                &:last-child
                    color: #F00
    .v-btn
        margin: 0
        padding 0
</style>
