<template>
    <VForm
        class="comment-form"
        @submit.prevent="submit"
    >
        <VContainer
            fluid
            grid-list-md
        >
            <VTextarea
                v-model="comment.content"
                :append-icon="appendIcon"
                prepend-icon="attach_file"
                auto-grow
                autofocus
                row-height="12"
                background-color="rgba(156, 153, 153, 0.06)"
                :clearable="newcomment"
                counter
                light
                box
                :loading="isLoading"
                label="Replay"
                @click:append="clearContent"
            />
            <VBtn
                :disabled="!hascontent"
                type="submit"
                light
                color="success"
            >
                Replay
            </VBtn>
        </VContainer>
    </VForm>
</template>
<script>
export default {
    props: {
        comment: {
            type: Object,
            default () {
                return {}
            }
        },
        commentableType: {
            type: String,
            default () {
                return 'discussion_board'
            }
        },
        commentableId: {
            type: Number,
            required: true
        },
        newcomment: {
            type: Boolean,
            default: false
        }
    },
    data () {
        return {
            isLoading: false
        }
    },
    computed: {
        hascontent () {
            return typeof this.comment.content === 'string' && this.comment.content.length > 0
        },
        appendIcon () {
            return this.newcomment ? 'undefined' : 'clear'
        }
    },
    methods: {
        submit () {
            if (typeof this.comment.content !== 'string' || this.comment.content === '') {
                return
            }
            var params = {
                content: this.comment.content,
                commentable_id: this.commentableId,
                project_id: this.$route.params.project_id,
                commentable_type: this.commentableType
            }
            if (this.comment.id) {
                axios.put(`/api/comments/${this.comment.id}`, params)
                    .then((res) => {
                        this.$store.commit('setSnackbar',
                            {
                                message: res.data.message,
                                status: res.status,
                                color: 'success',
                                show: true
                            },
                            { root: true })
                        Bus.$emit('Comment:update', res.data.data)
                        this.$emit('clear')
                    })
                    .catch((error) => {
                        this.$store.commit('setSnackbar',
                            {
                                message: error.response.data.message,
                                status: error.response.status,
                                color: 'error',
                                show: true
                            },
                            { root: true })
                    })
            } else {
                axios.post('/api/comments', params)
                    .then((res) => {
                        this.$store.commit('setSnackbar',
                            {
                                message: res.data.message,
                                status: res.status,
                                color: 'success',
                                show: true
                            },
                            { root: true })
                        Bus.$emit('Comment:new', res.data.data)
                        this.comment.content = ''
                    })
                    .catch((error) => {
                        this.$store.commit('setSnackbar',
                            {
                                message: error.response.data.message,
                                status: error.response.status,
                                color: 'error',
                                show: true
                            },
                            { root: true })
                    })
            }
        },
        clearContent () {
            if (this.newcomment) {
                this.comment.content = ''
            }
            this.$emit('clear')
        }
    }
}
</script>
<style lang="stylus">
.comment-form
    .v-counter
        flex: 0 1 auto;
        font-size: 12px;
        min-height: 12px;
        line-height: 1;
        background: #333;
        padding: 5px;
        border-radius: 50%;
        color: #fff;
    .v-btn
        top: -35px
        left: 24px

</style>
