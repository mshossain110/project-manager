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
            <VBtn
                icon
                @click="showcomments = !showcomments"
            >
                <VIcon>comment</VIcon>
            </VBtn>
        </VToolbar>
        <VCardText class="white">
            <div class="description text--primary">
                {{ discussion.description }}
            </div>

            <Comments
                v-if="showcomments"
                :comments="comments"
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
            showcomments: false
        }
    },
    computed: {
        comments () {
            return this.discussion.comments ? this.discussion.comments.data : []
        }
    }
}
</script>
<style lang="stylus">

</style>
