<template>
    <VForm @submit.prevent="submit" class="list-form">
        <div class="input-ourter">
            <input type="text" v-model="list.title" placeholder="New Task List">
            <div class="icon-btns">
                <v-btn flat small icon color="gray lighten-2" @click="showDescription = !showDescription">
                    <v-icon small>insert_comment</v-icon>
                </v-btn>
                <v-btn flat small icon color="gray lighten-2" v-if="lock" @click="lock = !lock">
                    <v-icon small>lock</v-icon>
                </v-btn>
                <v-btn flat small icon color="gray lighten-2" v-if="!lock " @click="lock = !lock">
                    <v-icon small>lock_open</v-icon>
                </v-btn>
            </div>
        </div>
        <transition name="slide-y-transition">
            <textarea v-if="showDescription" v-model="list.decription" placeholder="Description"></textarea>
        </transition>
        
        
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
    data() {
        return {
            showDescription: false,
            lock: this.list.private,
        };
    },
    computed: {
    },
    methods: {
        submit() {
            let data = {
                id: this.list.id,
                title: this.list.title,
                decription: this.list.decription,
                project_id: parseInt(this.$route.params.id),
                private: this.lock
            };
            if (typeof this.list.id === "undefined") {
                this.$store.dispatch("List/addList", data)
                    .then(() => {
                        // Reset form using form class
                        Object.assign(this.$data, this.$options.data.call(this));
                    })
            } else {
                this.$store.dispatch("List/updateList", data)
                .then(() => {
                    // Reset form using form class
                    Object.assign(this.$data, this.$options.data.call(this));
                })
            }
        }
    }
};
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
