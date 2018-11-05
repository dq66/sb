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
                <v-text-field v-model="search" append-icon="search" label="查找分类" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table no-data-text="还没有分类哦,快去添加一条吧!" rows-per-page-text='每页显示条数' :headers="headers" :items="desserts"
                :search="search">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.label }}</td>
                    <td>{{ props.item.slug }}</td>
                    <td>{{ props.item.postcount }}</td>
                    <td>{{ props.item.description }}</td>
                    <td>{{ props.item.typescount }}</td>
                    <td>{{ props.item.order }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>{{ props.item.updated_at }}</td>
                    <td>
                        <div v-if="!props.item.deleted_at">
                            <v-tooltip top>
                                <v-btn @click="edit(props.item)" slot="activator" small fab dark color="blue">
                                    <v-icon dark>edit</v-icon>
                                </v-btn>
                                <span>编辑 '{{ props.item.label }}'</span>
                            </v-tooltip>

                            <v-tooltip top>
                                <v-btn @click="destroy(props.item)" slot="activator" small fab dark color="pink">
                                    <v-icon dark>delete</v-icon>
                                </v-btn>
                                <span>删除 '{{ props.item.label }}'</span>
                            </v-tooltip>
                        </div>
                        <div class="text-xs-center" v-else>
                            <v-tooltip top>
                                <v-btn @click="restore(props.item)" slot="activator" small fab dark color="orange">
                                    <v-icon dark>restore</v-icon>
                                </v-btn>
                                <span>恢复 '{{ props.item.label }}'</span>
                            </v-tooltip>

                            <v-tooltip top>
                                <v-btn @click="deletes(props.item)" slot="activator" small fab dark color="pink">
                                    <v-icon dark>delete</v-icon>
                                </v-btn>
                                <span>彻底删除 '{{ props.item.label }}'</span>
                            </v-tooltip>
                        </div>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    没有找到和 "{{ search }}" 有关的数据.
                </v-alert>
            </v-data-table>
        </v-card>
        <DiaLogs ref="showEle" :desse="desserts" :title="title" :detail="detail"></DiaLogs>
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
      dialog: false,
      detail: {},
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
          text: "别名",
          value: "slug"
        },
        {
          text: "该分类下文章总数",
          value: "postcount"
        },
        {
          text: "分类描述",
          value: "description"
        },
        {
          text: "子分类数量",
          value: "typescount"
        },
        {
          text: "排序",
          value: "order"
        },
        {
          text: "添加时间",
          value: "created_at"
        },
        {
          text: "最后更新时间",
          value: "updated_at"
        },
        {
          text: "操作",
          value: ""
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
      this.$Api.MetasGetAll().then(res => {
        this.desserts = res.data;
      });
    },
    showDialogs() {
      this.title = "新增数据";
      this.$refs["showEle"].handleShowModal();
    },
    // 刷新
    refresh() {
      this.init();
      this.$Message("刷新数据成功", {
        timeout: 3000,
        icon: "info"
      });
    },
    // 软删除
    destroy(item) {
      this.$Api
        .DestroyMetas({
          id: item.id
        })
        .then(req => {
          this.$Message(req.data.message, {
            timeout: 3000,
            icon: "info"
          });
          this.init();
          console.log("TCL: deletes -> req", req);
        })
        .catch(error => {
          this.$Message.error(error.response.data.message);
          this.init();
          console.log("TCL: deletes -> error", error.response.data.message);
        });
    },
    // 恢复软删除
    restore(item) {
      this.$Api
        .RestoreMetas({
          id: item.id
        })
        .then(req => {
          console.log("TCL: restore -> req", req);
          this.$Message(req.data.message, {
            timeout: 3000,
            icon: "info"
          });
          this.init();
        })
        .catch(error => {
          console.log("TCL: restore -> error", error);
          this.$Message.error(error.response.data.message);
          this.init();
        });
    },
    // 彻底删除
    deletes(item) {
      this.$Api
        .DeleteMetas({
          id: item.id
        })
        .then(req => {
          console.log("TCL: deletes -> req", req);
          this.$Message(req.data.message, {
            timeout: 3000,
            icon: "info"
          });
          this.init();
        })
        .catch(error => {
          console.log("TCL: deletes -> error", error);
          this.$Message.error(error.response.data.message);
          this.init();
        });
    },
    // 编辑
    edit(item) {
      this.detail = item;
      this.title = `修改 "${item.label}"`;
      this.$refs["showEle"].handleShowModal();
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
