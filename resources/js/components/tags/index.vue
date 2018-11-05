<template>
    <div>
        <v-card flat>
            <v-card-title>
                <div class="text-xs-center">
                    <!-- <v-btn fab small dark color="light-blue" >
                        <v-icon dark>add</v-icon>
                    </v-btn>
                    <v-btn fab small dark color="pink">
                        <v-icon dark>remove</v-icon>
                    </v-btn> -->

                    <v-btn fab small dark color="grey" @click="refresh">
                        <v-icon dark>refresh</v-icon>
                    </v-btn>
                </div>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="查找标签" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table no-data-text="还没有标签哦,快去添加一条吧!" rows-per-page-text='每页显示条数' :headers="headers" :items="desserts"
                :search="search">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.text }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>{{ props.item.updated_at }}</td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    没有找到和 "{{ search }}" 有关的数据.
                </v-alert>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
export default {
    data(){
        return {
            search: "",
            headers: [
                {
                text: "ID",
                value: "id"
                },
                {
                text: "类型名",
                value: "label"
                },
                {
                text: "添加时间",
                value: "created_at"
                },
                {
                text: "最后更新时间",
                value: "updated_at"
                }
            ],
            desserts: []
        }
    },
    created(){
        this.init();
    },
    methods:{
        init(){
            this.$Api.TagsGetAll().then(res => {
                this.desserts = res.data;
            })
        },
        // 刷新
        refresh() {
        this.init();
        this.$Message("刷新数据成功", {
            timeout: 3000,
            icon: "info"
        });
        }
    }
}
</script>

<style>

tbody tr:nth-child(odd) {
  background: #f4f4f4;
}
</style>
