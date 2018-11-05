<template>
    <div>
        <v-card flat>
            <v-card-title>
                <div class="text-xs-center">
                    <v-btn fab small dark color="grey" @click="refresh">
                        <v-icon dark>refresh</v-icon>
                    </v-btn>
                </div>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-data-table no-data-text="还没有评论哦,快去添加一条吧!" rows-per-page-text='每页显示条数' :headers="headers" :items="desserts">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.article.title }}</td>
                    <td>{{ props.item.username }}</td>
                    <td>{{ props.item.email }}</td>
                    <td>{{ props.item.content }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>
                        <v-tooltip top>
                            <v-btn @click="edit(props.item)" slot="activator" small fab dark color="blue">
                                <v-icon dark>edit</v-icon>
                            </v-btn>
                            <span>编辑</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <v-btn @click="reply(props.item)" slot="activator" small fab dark color="purple">
                                <v-icon dark>edit</v-icon>
                            </v-btn>
                            <span>回复</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <v-btn @click="destroy(props.item)" slot="activator" small fab dark color="pink">
                                <v-icon dark>delete</v-icon>
                            </v-btn>
                            <span>删除</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    没有找到和 "{{ search }}" 有关的数据.
                </v-alert>
            </v-data-table>
        </v-card>
        <Dialogs ref="showEle" :desse="desserts" :title="title" :name="name" :detail="detail"></Dialogs>
    </div>
</template>

<script>
import Dialogs from "./dialogs";
export default {
    components: {
        Dialogs
    },
    data(){
        return {
            title: "",
            name: "",
            dialog: false,
            detail: {},
            search: "",
            headers: [
                {
                text: "ID",
                value: "id"
                },
                {
                text: "文章标题",
                value: "article"
                },
                {
                text: "评论作者",
                value: "username"
                },{
                text: "邮箱",
                value: "email"
                },{
                text: "内容",
                value: "content"
                },
                {
                text: "评论时间",
                value: "created_at"
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
            this.$Api.CommentAll().then(res => {
                this.desserts = res.data;
            })
        },
        edit(item){
            this.title = `编辑评论`;
            this.name = `编辑内容`;
            this.detail = item;
            this.$refs["showEle"].handleShowModal();
        },
        reply(item){
            this.title = `回复"${item.username}"`;
            this.name = `回复内容`;
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
