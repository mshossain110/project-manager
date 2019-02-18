<template>
    <VCard>
        <form @submit.prevent="submit()">
            <VCardTitle>
                <span
                    v-if="project.id"
                    class="headline"
                >
                    Update Project
                </span>
                <span
                    v-else
                    class="headline"
                >
                    New Project
                </span>
            </VCardTitle>

            <VCardText>
                <VContainer grid-list-md>
                    <VLayout wrap>
                        <VFlex
                            xs12
                        >
                            <VTextField
                                v-model="project.title"
                                :error-messages="errors.collect('title')"
                                label="Title"
                            />
                        </VFlex>
                        <VFlex
                            xs12
                        >
                            <VTextarea
                                v-model="project.description"
                                name="description"
                                box
                                label="Description"
                                :error-messages="errors.collect('description')"
                                auto-grow
                            />
                        </VFlex>
                    </VLayout>
                </VContainer>

                <small>*indicates required field</small>
            </VCardText>
            <VDivider />
            <VCardActions>
                <VSpacer />
                <VBtn
                    color="blue darken-1"
                    flat
                    @click.native="$emit('close', false)"
                >
                    Close
                </VBtn>
                <VBtn
                    color="blue darken-1"
                    flat
                    type="submit"
                >
                    Save
                </VBtn>
            </VCardActions>
        </form>
    </VCard>
</template>

<script>
// import Multiselect from 'vue-multiselect'
import { mapState } from 'vuex'

export default {
    components: {
        // Multiselect
    },
    $_veeValidate: {
        validator: 'new'
    },
    props: {
        project: {
            type: Object,
            default () {
                return {
                    title: '',
                    description: '',
                    category: '',
                    budget: '',
                    password: '',
                    assaingee: []
                }
            }
        }
    },

    data: () => ({

    }),

    computed: {
        // ...mapState('Users', ['permissions'])
    },
    created () {

    },
    methods: {
        submit () {
            this.$validator.validateAll()
            const project = {
                id: this.project.id,
                title: this.project.title,
                description: this.project.description,
                category: this.project.name,
                budget: this.project.budget
            }
            if (!this.project.id) {
                this.$store.dispatch('Projects/addProject', project)
                    .then(() => {
                        this.clear()
                        this.$emit('close', false)
                    })
            } else {
                this.$store.dispatch('Users/updateUser', project)
                    .then(() => {
                        this.$emit('close', false)
                    })
            }
        },
        clear () {
            this.project = {
                title: '',
                description: '',
                category: '',
                email: '',
                permissions: [],
                role: 0,
                avatar: ''
            }
        }
    }
}
</script>
