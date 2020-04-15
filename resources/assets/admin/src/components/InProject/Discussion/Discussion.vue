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
                @click="showeditform = !showeditform"
            >
                <VIcon>edit</VIcon>
            </VBtn>
            <VBtn
                icon
                @click="showcomments = !showcomments"
            >
                <VIcon>comment</VIcon>
            </VBtn>
             <VBtn
                icon
               @click="deleteDiscussion(discussion)"
            >
                <VIcon>delete</VIcon>
            </VBtn>
        </VToolbar>
        <DiscussionForm v-if="showeditform" :discuss="discussion"/>
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
import DiscussionForm from './DiscussionForm'
export default {
    components: {
        Comments,
        DiscussionForm
    },
    props: {
        discussion: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            showcomments: false,
            showeditform:false
        }
    },
    computed: {
        comments () {
            return this.discussion.comments ? this.discussion.comments.data : []
        }
    },
    methods:{
        deleteDiscussion(discussion){
            this.$store.dispatch('Discussion/deleteDiscussion',discussion)
        }
    }
}
</script>
<style lang="stylus">

</style>
