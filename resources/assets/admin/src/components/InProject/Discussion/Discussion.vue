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

            <Comments
                :comments="discussion.comments.data"
                commentable-type="discussion_board"
                :commentable-id="discussion.id"
            />
        </VCardText>
    </VCard>
</template>

<script>
import Comments from '@ac/InProject/Comment/Comments.vue'

export default {
    components: {
        Comments
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

    }
}
</script>
<style lang="stylus">

</style>
