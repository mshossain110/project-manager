<template>
    <VForm
        class="comment-form"
        @submit.prevent="$emit('submit')"
    >
        <VContainer
            fluid
            grid-list-md
        >
            <VTextarea
                v-model="comment.content"
                auto-grow
                autofucus
                clearable
                counter
                light
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
        }
    },
    computed: {
        hascontent () {
            return typeof this.comment.content === 'string' && this.comment.content.length > 0
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
