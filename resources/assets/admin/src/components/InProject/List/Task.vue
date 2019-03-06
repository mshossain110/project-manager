<template>
    <div class="task">
        <div class="task-title">{{task.title}}</div>
        <div class="action">
            <VBtn
                    flat
                    icon
                    small
                    color="red lighten-2"
                >
                    <VIcon>add</VIcon>
                </VBtn>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    data () {
        return {

        }
    }
}
</script>

<style lang="stylus">
.task
    display flex
    justify-content space-between
    align-items center
    padding 8px 20px
    border-top: 1px solid #FFF
    border-bottom: 1px solid #ddd
    &:hover
        background: #fcfcfc
        border-top: 1px solid #ddd
        border-bottom: 1px solid #ddd

    .v-btn
        margin: 0
        padding 0

</style>
