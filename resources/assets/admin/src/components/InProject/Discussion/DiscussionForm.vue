<template>
    <VForm
        class="discussion-form"
        @submit.prevent="submit"
    >
        <div class="input-ourter">
            <input
                v-model="discuss.title"
                type="text"
                placeholder="New Topic Title"
            >
            <div class="icon-btns">
                <VBtn
                    v-if="lock"
                    flat
                    small
                    icon
                    color="gray lighten-2"
                    @click="lock = !lock"
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
                    @click="lock = !lock"
                >
                    <VIcon small>
                        lock_open
                    </VIcon>
                </VBtn>
            </div>
        </div>

        <textarea
            v-model="discuss.description"
            placeholder="Description"
        />
        <VBtn
            color="primary"
            type="submit"
        >
            Submit
        </VBtn>
    </VForm>
</template>

<script>
export default {
    props: {
        discuss: {
            type: Object,
            required: true
        }
    },
    data () {
        return {

            lock: this.discuss.private
        }
    },
    computed: {
    },
    methods: {
        submit () {
            let data = {
                id: this.discuss.id,
                title: this.discuss.title,
                description: this.discuss.description,
                project_id: parseInt(this.$route.params.project_id),
                private: this.lock
            }
            if (typeof this.discuss.id === 'undefined') {
                this.$store.dispatch('Discussion/addDiscussion', data)
                    .then(() => {
                        // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this))
                    })
            } else {
                this.$store.dispatch('Discussion/updateDiscussion', data)
                    .then(() => {
                    // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this))
                    })
            }
        }
    }
}
</script>
<style lang="stylus">
.discussion-form
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

    textarea
        width: 100%
        padding: 10px
        border-bottom: 1px solid #dcd
        &:focus
            border-color: #666
            outline: none
</style>
