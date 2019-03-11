<template>
    <VForm
        class="task-form"
        @submit.prevent="submit"
    >
        <div class="input-ourter">
            <input
                v-model="task.title"
                type="text"
                placeholder="New Task"
            >
            <div class="icon-btns">
                <VBtn
                    flat
                    small
                    icon
                    color="gray lighten-2"
                    @click="toggleDescription"
                >
                    <VIcon small>
                        insert_comment
                    </VIcon>
                </VBtn>
                <VBtn
                    v-if="lock"
                    flat
                    small
                    icon
                    color="gray lighten-2"
                    @click="toggleLock"
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
                    @click="toggleLock"
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
                                >{{ momentFormat(task.start_at) }} </span>
                                <span
                                    v-if="task.due_date"
                                    class="red--text"
                                > - {{ momentFormat(task.due_date) }}</span>
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
        </div>
        <Transition name="slide-y-transition">
            <textarea
                v-if="showDescription"
                v-model="task.description"
                placeholder="Description"
            />
        </Transition>
    </VForm>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        task: {
            type: Object,
            required: true
        },
        list: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            showDescription: false,
            lock: this.task.private || false,
            datepicker: false
        }
    },
    computed: {
        today () {
            return moment().toISOString()
        },
        dueMin () {
            return this.task.start_at ? moment(this.task.start_at).toISOString() : moment().toISOString()
        }
    },
    mounted () {
        this.inputFocus()
    },
    methods: {
        momentFormat (date) {
            if (date) {
                return moment(date).format('DD MMM')
            }
        },
        closeDatePicker () {
            this.datepicker = false
            this.inputFocus()
        },
        toggleDescription () {
            this.showDescription = !this.showDescription
            if (!this.showDescription) {
                this.inputFocus()
            } else {
                this.$nextTick(() => {
                    this.$el.querySelector('textarea').focus()
                })
            }
        },
        toggleLock () {
            this.lock = !this.lock
            this.$nextTick(() => {
                this.inputFocus()
            })
        },
        submit () {
            let data = {
                id: this.task.id,
                title: this.task.title,
                description: this.task.description,
                project_id: parseInt(this.$route.params.id),
                list_id: this.list.id,
                start_at: this.task.start_at,
                due_date: this.task.due_date,
                private: this.lock
            }
            if (typeof this.task.id === 'undefined') {
                this.$store.dispatch('List/addTask', data)
            } else {
                this.$store.dispatch('List/updateTask', data)
            }
        },
        inputFocus () {
            this.$el.querySelector('input').focus()
        }
    }

}
</script>
<style lang="stylus">
.task-form
    .input-ourter
        position: relative
        overflow: hidden
        input
            padding: 10px
            width: 100%
            border-style: solid
            border-color: #dcd
            border-width: 0px 0px 1px 0px
            margin-bottom: 5px
            transition: all 0.2
            &:focus
                border-color: #666
                outline: none
        .icon-btns
            position: absolute;
            right: 20px;
            top: 6px;
            .v-btn
                padding: 0px;
                margin: 0px;
        .pm-date-picker
            width: auto
        .v-menu
            display: inline

    textarea
        width: 100%
        padding: 10px
        border-bottom: 1px solid #dcd
        &:focus
            border-color: #666
            outline: none
</style>
