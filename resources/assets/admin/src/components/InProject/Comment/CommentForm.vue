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
                auto-grow
                autofocus
                row-height="12"
                background-color="rgba(156, 153, 153, 0.06)"
                clearable
                counter
                light
                box
                :loading="isLoading"
                label="Replay"
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

            axios.post('/api/comments', params)
                .then(() => {

                })
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

</style>
