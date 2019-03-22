<template>
    <VCard>
        <VToolbar
            dark
            color="blue"
            dense
            flat
        >
            <VToolbarTitle>{{ discussion.title }}</VToolbarTitle>
            <VSpacer />
            <VBtn icon>
                <VIcon>comment</VIcon>
            </VBtn>
        </VToolbar>
        <VCardText class="white ">
            <div class="description text--primary">
                {{ discussion.description }}
            </div>

            <CommentForm
                :comment="newComment"
                @submit="postComment"
            />
        </VCardText>
    </VCard>
</template>

<script>
import CommentForm from './CommentForm.vue'

export default {
    components: {
        CommentForm
    },
    props: {
        discussion: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            newComment: {}
        }
    },
    computed: {

    },
    created () {

    },
    methods: {
        postComment () {

        }
    }
}
</script>
<style lang="stylus">

</style>
