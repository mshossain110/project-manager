<template>
    <VCard flat>
        <VCardText
            v-if="!editmode"
            primary-title
        >
            <div class="descriptipn">
                <div> {{ comment.content }} </div>
            </div>
            <div class="commenttime">
                <VBtn
                    flat
                    icon
                    small
                    @click="editmode = !editmode"
                >
                    <VIcon small>
                        edit
                    </VIcon>
                </VBtn>
                <VBtn
                    flat
                    icon
                    small
                    color="red"
                    @click="deleteComment"
                >
                    <VIcon small>
                        delete
                    </VIcon>
                </VBtn>
                <span>{{ momentFormat(comment.created_at.date, 'DD MMM YYYY') }}</span>
                <span>{{ momentFormat(comment.created_at.date, 'HH: MM A') }}</span>
            </div>
        </VCardText>
        <CommentForm
            v-if="editmode"
            :comment="comment"
            :commentable-type="commentableType"
            :commentable-id="commentableId"
            @clear="clearFrom"
        />
    </VCard>
</template>

<script>
import CommentForm from './CommentForm'
export default {
    components: {
        CommentForm
    },
    props: {
        comment: {
            type: Object,
            required: true
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
            editmode: false
        }
    },
    computed: {

    },
    created () {

    },
    methods: {
        clearFrom () {
            this.editmode = false
        },
        deleteComment () {
            axios.delete(`/api/comments/${this.comment.id}`)
                .then((res) => {
                    this.$store.commit('setSnackbar',
                        {
                            message: res.data.message,
                            status: res.status,
                            color: 'success',
                            show: true
                        },
                        { root: true })
                    Bus.$emit('Comment:delete', this.comment.id)
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
    }
}
</script>
