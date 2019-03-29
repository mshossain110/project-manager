<template>
    <VLayout
        d-block
    >
        <VFlex
            xs12
            d-flex
        >
            <VCard>
                <VToolbar
                    dense
                    flat
                >
                    <VToolbarItems class="hidden-sm-and-down">
                        <VBtn
                            flat
                            :to="{name:'project_active'}"
                        >
                            Active
                        </VBtn>
                        <VBtn
                            flat
                            :to="{name:'project_complete'}"
                        >
                            Complete
                        </VBtn>
                        <VBtn
                            flat
                            :to="{name:'project_favourite'}"
                        >
                            Favourite
                        </VBtn>
                        <VBtn
                            flat
                            :to="{name:'project_all'}"
                        >
                            All
                        </VBtn>
                    </VToolbarItems>

                    <VSpacer />
                    <select
                        v-model="category"
                        class="category_select"
                        name="category"
                    >
                        <option value="">
                            Category
                        </option>
                        <option>Foo</option>
                        <option>Foo</option>
                        <option>Foo</option>
                    </select>
                    <VBtn icon>
                        <VIcon>search</VIcon>
                    </VBtn>

                    <VBtn
                        icon
                        @click="openform = true"
                    >
                        <VIcon>person_add</VIcon>
                    </VBtn>

                    <VBtn icon>
                        <VIcon>more_vert</VIcon>
                    </VBtn>
                </VToolbar>

                <RouterView />
            </VCard>
        </VFlex>
        <VDialog
            v-model="openform"
            max-width="500px"
        >
            <ProjectForm @close="openform = false" />
        </VDialog>
    </VLayout>
</template>

<script>

import ProjectForm from './ProjectFrom.vue'
export default {
    components: {
        ProjectForm
    },
    data () {
        return {
            openform: false,
            category: '',
            items: ['Foo', 'Bar', 'Fizz', 'Buzz']
        }
    }
}
</script>

<style>
  .card--flex-toolbar {
    margin-top: -64px;
  }

  .category_select {
    border: 1px solid #ddd;
    padding: 16px 10px;
    -webkit-appearance: menulist-button;
    display: inline-block;
    width: 100px;
    height: 40px;
  }
</style>
