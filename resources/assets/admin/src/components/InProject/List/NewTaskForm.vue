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

                <VMenu
                    ref="assign"
                    v-model="assign"
                    :nudge-width="200"
                    :nudge-right="240"
                    offset-y
                    content-class="assign-popover"
                    transition="scale-transition"
                >
                    <template v-slot:activator="{ on }">
                        <VBtn
                            flat
                            small
                            icon
                            color="gray lighten-2"
                            class="pm-assign"
                            v-on="on"
                        >
                            <VIcon small>
                                persone
                            </VIcon>
                        </VBtn>
                    </template>
                    <Multiselect
                        v-model="assign_user"
                        :options="projectUsers"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="true"
                        :show-labels="true"
                        :searchable="true"
                        placeholder="Select User"
                        select-label=""
                        selected-label="selected"
                        deselect-label=""
                        label="name"
                        track-by="id"
                        :allow-empty="true"
                    >
                        <template
                            slot="option"
                            slot-scope="props"
                        >
                            <img
                                class="option__image"
                                :src="props.option.avatar"
                                :alt="props.option.name"
                            >
                            <div class="option__desc">
                                <span class="option__title">{{ props.option.name }}</span>
                            </div>
                        </template>
                    </Multiselect>
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
import Multiselect from 'vue-multiselect'
import moment from 'moment'
import { mapGetters } from 'vuex'

export default {
    components: {
        Multiselect
    },
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
            datepicker: false,
            assign: false,
            assign_user: typeof this.task.assignees !== 'undefined' ? this.task.assignees.data : []

        }
    },
    computed: {
        ...mapGetters('Projects', ['projectUsers']),
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
            let assignees = this.assign_user.map(u => u.id)
            let data = {
                id: this.task.id,
                title: this.task.title,
                description: this.task.description,
                project_id: parseInt(this.$route.params.project_id),
                list_id: this.list.id,
                start_at: this.task.start_at,
                due_date: this.task.due_date,
                private: this.lock,
                assignees: assignees
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

.v-menu__content.assign-popover
    overflow-y: visible;
    overflow-x: visible;
    background: #FFF;
    contain: none;
    .multiselect
        padding: 10px;
        width: 91%;
    .multiselect__single
        display: none;
    .multiselect__tags-wrap
        display: none
    .multiselect__content-wrapper
        position: relative;
        display: block !important;
        margin-top: 13px;
        height: auto !important;
        .multiselect__content
            border-radius: 3px;
            border: 1px solid #ECECEC;
            display: block;
            max-height: 148px;
            .multiselect__option
                display: flex;
                align-items: center;
                padding: 2px 0 0 6px;
                background: #fff;
                font-size: 13px;
                color: #788383;
                font-weight: normal;
                font-family: "Helvetica Neue", sans-serif;
                .option__image
                    margin-right: 5px;
                    height: 24px;
                    width: 24px;
                    border-radius: 12px;
                &.multiselect__option--selected
                    background: #1ABC9C;
                    color: #fff;
                    border: 1px solid #fff;

            .multiselect__option--highlight:after
                background: none;
                color: #A5ACB1;
            .multiselect__option:after
                content: "\2713   ";
                color: #d4d6d6;
            .multiselect__option--selected:after
                content: "X";
                color: #d4d6d6;
</style>
