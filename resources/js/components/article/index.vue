<template>
    <div>
        <v-card flat>
            <v-card-title>
                <div class="text-xs-center">
                    <v-btn fab small dark color="light-blue" @click="showDialogs">
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
                <v-text-field v-model="search" append-icon="search" label="查找文章" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table rows-per-page-text="每页条数" :headers="headers" :items="desserts" :search="search">
                <template slot="items" slot-scope="props">
                    <td class="text-xs-left">{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.title }}</td>
                    <td class="text-xs-left">{{ props.item.slug }}</td>
                    <td class="text-xs-left">{{ props.item.metas.label }}</td>
                    <td class="text-xs-left">{{ props.item.author.name }}</td>
                    <td class="text-xs-left">
                        <span v-if="props.item.state">
                            发布
                        </span>
                        <span v-else>
                            草稿
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="props.item.is_top">
                            置顶
                        </span>
                        <span v-else>
                            未置顶
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="props.item.criticism">
                            允许
                        </span>
                        <span v-else>
                            不允许
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="props.item.publicstate==='publish'">
                            公开
                        </span>
                        <span v-else-if="props.item.publicstate==='hidden'">
                            隐藏
                        </span>
                        <span v-else-if="props.item.publicstate==='password'">
                            密码保护
                        </span>
                        <span v-else>
                            私有
                        </span>
                    </td>
                    <td class="text-xs-left">{{ props.item.criticismcount }}</td>
                    <td class="text-xs-left">{{ props.item.order }}</td>
                    <td class="text-xs-left">
                        <div v-if="!props.item.deleted_at">
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
                        </div>
                        <div class="text-xs-center" v-else>
                            <v-tooltip top>
                                <v-btn @click="restore(props.item)" slot="activator" small fab dark color="orange">
                                    <v-icon dark>restore</v-icon>
                                </v-btn>
                                <span>恢复 '{{ props.item.title }}'</span>
                            </v-tooltip>

                            <v-tooltip top>
                                <v-btn @click="deletes(props.item)" slot="activator" small fab dark color="pink">
                                    <v-icon dark>delete</v-icon>
                                </v-btn>
                                <span>彻底删除 '{{ props.item.title }}'</span>
                            </v-tooltip>
                        </div>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    没有找到和 "{{ search }}" 有关的文章.
                </v-alert>
            </v-data-table>
        </v-card>
        <DiaLogs ref="showEle" :title="title" :detail="detail"></DiaLogs>
    </div>

</template>
<script>
import DiaLogs from "./dialogs";
export default {
  components: {
    DiaLogs
  },
  name: "",
  data() {
    return {
      title: "",
      detail: {},
      search: "",
      headers: [
        {
          text: "ID",
          value: "id"
        },
        {
          text: "文章标题",
          sortable: false,
          value: "title"
        },
        {
          text: "别名",
          value: "slug"
        },
        {
          text: "分类",
          value: "metas"
        },
        {
          text: "作者",
          value: "user"
        },
        {
          text: "发布状态",
          value: "state"
        },
        {
          text: "是否置顶",
          value: "is_top"
        },
        {
          text: "是否允许评论",
          value: "criticism"
        },
        {
          text: "公开度",
          value: "publicstate"
        },
        {
          text: "评论数",
          value: "criticismcount"
        },
        {
          text: "排序",
          value: "order"
        },
        {
          text: "操作"
        }
      ],
      desserts: []
    };
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      this.$Api.ArticleGetAll().then(res => {
        this.desserts = res.data;
      });
    },
    showDialogs() {
      this.title = "新增文章";
      this.$refs["showEle"].handleShowModal();
    },
    edit(item){
      this.detail = item;
      this.title = `修改"${item.title}"`;
      this.$refs["showEle"].handleShowModal();
    },
    //软删除
    destroy(item){
        this.$Api.ArticleDestroy({id:item.id})
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
    //恢复软删除
    restore(item){

        this.$Api.ArticleRestore({id:item.id})
        .then(req => {
            console.log("TCL: restore -> req", req);
            this.$Message(req.data.message, {
                timeout: 3000,
                icon: "info"
            });
            this.init();
        })
        .catch(error => {
          this.$Message.error(error.response.data.message);
          this.init();
          console.log("TCL: restore -> error", error.response.data.message);
        });
    },
    //彻底删除
    deletes(item){
        this.$Api.ArticleDelete({id:item.id})
        .then(req  => {
            console.log("TCL: deletes -> req", req);
            this.$Message(req.data.message, {
                timeout: 3000,
                icon: "info"
            });
            this.init();
        })
        .catch(error => {
          this.$Message.error(error.response.data.message);
          this.init();
          console.log("TCL: deletes -> error", error.response.data.message);
        });
    },
    refresh() {
      this.init();
      this.$Message("刷新数据成功", {
        timeout: 3000,
        icon: "info"
      });
    }
  }
};
</script>

<style>
.theme--light.v-table tbody tr:not(:last-child) {
  border-bottom: none;
}

.theme--light.v-datatable .v-datatable__actions {
  border-top: none;
}

.theme--light.v-table thead tr:first-child {
  border-bottom: none;
}

tbody tr:nth-child(odd) {
  background: #f4f4f4;
}
</style>
