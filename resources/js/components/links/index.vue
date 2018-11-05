<template>
    <div>
        <v-card flat>
            <v-card-title>
                <div class="text-xs-center">
                    <v-btn fab small dark color="light-blue" @click="showDialogs" >
                        <v-icon dark>add</v-icon>
                    </v-btn>
                    <v-btn fab small dark color="pink">
                        <v-icon dark>remove</v-icon>
                    </v-btn>
                    <v-btn fab small dark color="grey" @click="refresh">
                        <v-icon dark>refresh</v-icon>
                    </v-btn>
                </div>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="查找友情链接" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table no-data-text="还没有链接哦,快去添加一条吧!" rows-per-page-text='每页显示条数' :headers="headers" :items="desserts"
                :search="search">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.id }}</td>
                    <td></td>
                    <td>{{ props.item.title }}</td>
                    <td>{{ props.item.connect }}</td>
                    <td>{{ props.item.describe }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>{{ props.item.updated_at }}</td>
                    <td>
                        <v-tooltip top>
                            <v-btn @click="edit(props.item)" slot="activator" small fab dark color="blue">
                                <v-icon dark>edit</v-icon>
                            </v-btn>
                            <span>编辑 '{{ props.item.title }}'</span>
                        </v-tooltip>

                        <v-tooltip top>
                            <v-btn @click="destroy(props.item)" slot="activator" small fab dark color="pink">
                                <v-icon dark>delete</v-icon>
                            </v-btn>
                            <span>删除 '{{ props.item.title }}'</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    没有找到和 "{{ search }}" 有关的数据.
                </v-alert>
            </v-data-table>
        </v-card>
        <Add ref="showEle" :desse="desserts" :title="title" :detail="detail"></Add>
    </div>
</template>

<script>
import Add from "./add";
export default {
    components: {
        Add
    },
    data(){
        return {
            title: "",
            dialog: false,
            detail: {},
            search: "",
            headers: [
                {
                text: "ID",
                value: "id"
                },
                {
                text: "图片",
                value: "avatar"
                },
                {
                text: "标题",
                value: "title"
                },{
                text: "链接",
                value: "connect"
                },{
                text: "描述",
                value: "describe"
                },
                {
                text: "添加时间",
                value: "created_at"
                },
                {
                text: "最后更新时间",
                value: "updated_at"
                },{
                text: "操作"
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
            this.$Api.LinksAll().then(res => {
                this.desserts = res.data;
            })
        },
        showDialogs() {
            this.title = "新增数据";
            this.$refs["showEle"].handleShowModal();
        },
        edit(item){
            this.title = `修改"${item.title}"`;
            this.detail = item;
            this.$refs["showEle"].handleShowModal();
        },
        destroy(item){
            this.$Api.LinksDelete({id:item.id})
            .then(req => {
                this.$Message(req.data.message, {
                    timeout: 3000,
                    icon: "info"
                });
                this.init();
                console.log("TCL: destroy -> req", req);
            })
            .catch(error => {
                this.$Message.error(error.response.data.message);
                this.init();
                console.log("TCL: destroy -> error", error.response.data.message);
            });
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
