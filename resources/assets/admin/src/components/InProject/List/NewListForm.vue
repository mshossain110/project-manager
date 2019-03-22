<template>
    <VForm
        class="list-form"
        @submit.prevent="submit"
    >
        <div class="input-ourter">
            <input
                v-model="list.title"
                type="text"
                placeholder="New Task List"
            >
            <div class="icon-btns">
                <VBtn
                    flat
                    small
                    icon
                    color="gray lighten-2"
                    @click="showDescription = !showDescription"
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
        <Transition name="slide-y-transition">
            <textarea
                v-if="showDescription"
                v-model="list.decription"
                placeholder="Description"
            />
        </Transition>
    </VForm>
</template>

<script>
export default {
    props: {
        list: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            showDescription: false,
            lock: this.list.private
        }
    },
    computed: {
    },
    methods: {
        submit () {
            let data = {
                id: this.list.id,
                title: this.list.title,
                decription: this.list.decription,
                project_id: parseInt(this.$route.params.project_id),
                private: this.lock
            }
            if (typeof this.list.id === 'undefined') {
                this.$store.dispatch('List/addList', data)
                    .then(() => {
                        // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this))
                    })
            } else {
                this.$store.dispatch('List/updateList', data)
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
.list-form
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
