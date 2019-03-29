<template>
    <div class="list">
        <div class="list-collumn">
            <div
                class="list-title"
                @click="singleList"
            >
                {{ list.title }}
            </div>

            <div class="task-show">
                <VBtn
                    color="green darken-1"
                    dark
                    small
                    :flat="!incompleteTasks"
                    @click="incompleteTasks = true"
                >
                    Incomplete
                </VBtn>
                <VBtn
                    color="red darken-1"
                    dark
                    small
                    :flat="incompleteTasks"
                    @click="incompleteTasks = false"
                >
                    complete
                </VBtn>
                <VProgressCircular
                    :value="progress"
                    size="24"
                    width="2"
                    color="#4caf50"
                />
            </div>

            <div class="list-action">
                <VBtn
                    flat
                    icon
                    small
                    color="red lighten-2"
                    @click="taskForm = !taskForm"
                >
                    <VIcon>add</VIcon>
                </VBtn>
            </div>
        </div>

        <div class="task-container">
            <ul v-if="incompleteTasks">
                <li
                    v-for="task in list.incomplete_tasks.data"
                    :key="task.id"
                >
                    <Task :task="task" />
                </li>
            </ul>
            <ul v-else>
                <li
                    v-for="task in list.complete_tasks.data"
                    :key="task.id"
                >
                    <Task :task="task" />
                </li>
            </ul>

            <Transition name="fade-transition">
                <NewTaskForm
                    v-if="taskForm"
                    :list="list"
                    :task="{}"
                />
            </Transition>
        </div>
    </div>
</template>

<script>
import NewTaskForm from './NewTaskForm.vue'
import Task from './Task.vue'
export default {
    components: {
        NewTaskForm, Task
    },
    props: {
        list: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            taskForm: false,
            incompleteTasks: true
        }
    },
    computed: {
        progress () {
            let meta = this.list.meta
            return (meta.total_complete_tasks / meta.total_tasks) * 100
        }
    },
    methods: {
        singleList () {
            this.$router.push({
                name: 'list-single',
                params: {
                    project_id: this.$route.params.project_id,
                    type: 'list',
                    id: this.list.id
                }
            })
        }
    }
}
</script>
<style lang="stylus">
.list .list-collumn
    display: flex
    justify-content: space-between
    align-items: center
    padding: 8px 20px
    border-top: 1px solid #FFF
    border-bottom: 1px solid #ddd
    &> div
        margin-right: 10px;
    &:hover
        background: #fcfcfc
        border-top: 1px solid #ddd
        border-bottom: 1px solid #ddd
    .v-btn
        margin: 0
        padding: 0
    .list-title
        font-size 18px
        font-weight 800
        flex-grow 1
    .task-show
        flex-grow 1
        .v-btn--small
            font-size: 10px;
            height: 22px;
        .v-progress-circular
            margin-left 20px
</style>
