<template>
    <VLayout row>
        <VFlex
            xs12
        >
            <VCard v-if="!isLoading">
                <VToolbar
                    flat
                    dense
                >
                    <VBtn
                        small
                        color="success"
                        @click="createDiscuss = !createDiscuss"
                    >
                        Craete New Topic
                    </VBtn>

                    <VSpacer />

                    <VBtn
                        flat
                    >
                        Filter
                    </VBtn>
                </VToolbar>
                <Transition name="slide-y-transition">
                    <DiscussionForm
                        v-if="createDiscuss"
                        :discuss="{}"
                    />
                </Transition>

                <div class="discussion-container">
                    <ul>
                        <li
                            v-for="discussion in discussions"
                            :key="discussion.id"
                        >
                            <Discussion :discussion="discussion" />
                        </li>
                    </ul>
                </div>
            </VCard>
        </VFlex>
    </VLayout>
</template>

<script>
import { mapState } from 'vuex'
import DiscussionForm from './DiscussionForm.vue'
import Discussion from './Discussion.vue'

export default {
    components: {
        DiscussionForm, Discussion
    },
    data () {
        return {
            isLoading: false,
            createDiscuss: false
        }
    },
    computed: {
        ...mapState('Discussion', ['discussions']),
        project_id () {
            return this.$route.params.id
        }
    },
    created () {
        this.isLoading = true
        this.$store.dispatch('Discussion/getDiscussions', { project_id: this.project_id })
            .then(() => {
                this.isLoading = false
            })
    }
}
</script>
<style lang="stylus">
.discussion-container
    margin-top: 20px
    ul
        margin: 0;
        padding: 0;
        list-style: none;
</style>
