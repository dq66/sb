<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap>
            <v-flex v-for="i in 8" :key="`${i}`" d-flex xs4>
                <v-hover>
                    <v-card :class="`elevation-${hover ? 12 : 2}`" flat slot-scope="{ hover }">
                        <v-img src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg" height="200px">
                        </v-img>

                        <v-card-title primary-title>
                            <div>
                                <div class="headline">TeeBlog</div>
                                <span class="grey--text">一个适合于写作的Blog</span>
                            </div>
                        </v-card-title>

                        <v-card-actions>
                            <v-btn flat>启用该主题</v-btn>
                            <v-btn flat>点个赞</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn icon @click="show = !show">
                                <v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
                            </v-btn>
                        </v-card-actions>
                        <v-slide-y-transition>
                            <v-card-text v-show="show">
                                I'm a thing. But, like most politicians, he promised more than he could deliver. You
                                won't
                                have time for sleeping, soldier, not with all the bed making you'll be doing. Then
                                we'll go
                                with that data file! Hey, you add a one and two zeros to that or we walk! You're going
                                to
                                do his laundry? I've got to find a way to escape.
                            </v-card-text>
                        </v-slide-y-transition>
                    </v-card>
                </v-hover>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      lorem: `Lorem ipsum dolor sit amet, mel at clita quando. Te sit oratio vituperatoribus, nam ad ipsum posidonium mediocritatem, explicari dissentiunt cu mea. Repudiare disputationi vim in, mollis iriure nec cu, alienum argumentum ius ad. Pri eu justo aeque torquatos.`,
      show: false
    };
  }
};
</script>

<style lang="" scoped>
</style>
