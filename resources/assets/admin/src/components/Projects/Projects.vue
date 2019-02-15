<template>
    <v-layout
        d-block
    >
        <v-flex
            xs12
            d-flex
        >
            <div class="pageTitle">
                <h2 class="headline">
                    <v-toolbar-side-icon />Projects
                </h2>
            </div>
        </v-flex>
        <v-flex
            xs12
            d-flex
        >
            <v-card>
                <v-toolbar flat>
                    <v-toolbar-items class="hidden-sm-and-down">
                        <v-btn flat>
                            Active
                        </v-btn>
                        <v-btn flat>
                            Complete
                        </v-btn>
                        <v-btn flat>
                            Favourite
                        </v-btn>
                        <v-btn flat>
                            All
                        </v-btn>
                    </v-toolbar-items>

                    <v-spacer />
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
                    <v-btn icon>
                        <v-icon>search</v-icon>
                    </v-btn>

                    <v-btn icon>
                        <v-icon>apps</v-icon>
                    </v-btn>

                    <v-btn icon>
                        <v-icon>more_vert</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-card-text style="height: 200px;" />
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    data () {
        return {
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
