<template>
    <div class="list">
        <div class="list-collumn">
            <div class="list-title">
                {{ list.title }}
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
            <Transition name="fade-transition">
                <NewTaskForm
                    v-if="taskForm"
                    :task="{}"
                />
            </Transition>
        </div>
    </div>
</template>

<script>
import NewTaskForm from './NewTaskForm.vue'
export default {
    components: {
        NewTaskForm
    },
    props: {
        list: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            taskForm: false
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
    &:hover
        background: #fcfcfc
        border-top: 1px solid #ddd
        border-bottom: 1px solid #ddd
    .v-btn
        margin: 0
        padding 0

</style>
