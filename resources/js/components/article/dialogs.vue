<template>
    <div>
        <v-layout row justify-center>
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card flat>
                    <v-toolbar dark color="cyan">
                        <v-btn icon dark @click.native="closewindow">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ title }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn @click="submodel" dark flat>Save</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container grid-list-md text-xs-center>
                        <v-layout row wrap>
                            <v-flex xs12 md9>
                                <v-card tile flat>
                                    <v-card-text>
                                        <v-form>
                                            <v-text-field v-model="Article.title" label="文章标题" required></v-text-field>
                                            <v-text-field v-model="Article.slug" label="文章别名" required></v-text-field>
                                        </v-form>
                                        <br>
                                        <mavon-editor ref=md v-model="Article.markdown"
                                            style="height:480px"></mavon-editor>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex xs12 md3>
                                <v-card tile flat>
                                    <v-card-text>
                                        <v-card-text>
                                            <v-subheader class="pa-0">分类</v-subheader>
                                            <v-select v-model="Article.metas_id" :items="types" label="选择分类" item-value="id" item-text="label"></v-select>
                                            <v-subheader class="pa-0">Tags</v-subheader>
                                            <v-combobox v-model="model" :filter="filter" :hide-no-data="!search" :items="items"
                                                :search-input.sync="search" hide-selected label="请输入Tags" multiple
                                                small-chips solo>
                                                <template slot="no-data">
                                                    <v-list-tile>
                                                        <span class="subheading">Create</span>
                                                        <v-chip :color="`${colors[nonce - 1]} lighten-3`" label small>
                                                            {{ search }}
                                                        </v-chip>
                                                    </v-list-tile>
                                                </template>
                                                <template v-if="item === Object(item)" slot="selection" slot-scope="{ item, parent, selected }">
                                                    <v-chip :color="`${item.color} lighten-3`" :selected="selected"
                                                        label small>
                                                        <span class="pr-2">
                                                            {{ item.text }}
                                                        </span>
                                                        <v-icon small @click="parent.selectItem(item)">close</v-icon>
                                                    </v-chip>
                                                </template>
                                                <template slot="item" slot-scope="{ index, item, parent }">
                                                    <v-list-tile-content>
                                                        <v-text-field v-if="editing === item" v-model="editing.text"
                                                            autofocus flat background-color="transparent" hide-details
                                                            solo @keyup.enter="edit(index, item)"></v-text-field>
                                                        <v-chip v-else :color="`${item.color} lighten-3`" dark label
                                                            small>
                                                            {{ item.text }}
                                                        </v-chip>
                                                    </v-list-tile-content>
                                                    <v-spacer></v-spacer>
                                                    <v-list-tile-action @click.stop>
                                                        <v-btn icon @click.stop.prevent="edit(index, item)">
                                                            <v-icon>{{ editing !== item ? 'edit' : 'check' }}</v-icon>
                                                        </v-btn>
                                                    </v-list-tile-action>
                                                </template>
                                            </v-combobox>
                                            <v-subheader class="pa-0">发布</v-subheader>
                                            <v-select @change="select" v-model="Article.state" :items="state" item-text="v"
                                                item-value="k" label="发布状态"></v-select>
                                            <div>
                                                <v-select @change="select" v-model="Article.publicstate" :items="publicstate"
                                                    item-text="v" item-value="k" label="公开度"></v-select>
                                                <div v-show="password">
                                                    <v-text-field v-model="Article.password" label="密码" required></v-text-field>
                                                </div>
                                                <div v-show="publish">
                                                    <v-checkbox v-model="Article.is_top" label="是否置顶" color="red" value=1
                                                        hide-details></v-checkbox>
                                                </div>
                                            </div>
                                            <v-radio-group v-model="Article.criticism" row>
                                                <v-radio label="允许评论" value="1"></v-radio>
                                                <v-radio label="不允许评论" value="0"></v-radio>
                                            </v-radio-group>
                                            <v-subheader class="pa-0">定时发布[不定时则不填写]</v-subheader>
                                            <v-menu ref="menu1" :close-on-content-click="false" v-model="menu1"
                                                :nudge-right="40" :return-value.sync="date" lazy transition="scale-transition"
                                                offset-y full-width min-width="290px">
                                                <v-text-field slot="activator" v-model="date" label="请选择日期"
                                                    prepend-icon="event" readonly></v-text-field>
                                                <v-date-picker v-model="date" @input="$refs.menu1.save(date)"></v-date-picker>
                                            </v-menu>
                                            <v-menu ref="menu2" :close-on-content-click="false" v-model="menu2"
                                                :nudge-right="40" :return-value.sync="time" lazy transition="scale-transition"
                                                offset-y full-width max-width="290px" min-width="290px">
                                                <v-text-field prepend-icon="timer" slot="activator" v-model="time"
                                                    label="请选择时间" readonly></v-text-field>
                                                <v-time-picker v-if="menu2" v-model="time" @change="$refs.menu2.save(time)"></v-time-picker>
                                            </v-menu>
                                        </v-card-text>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>
<script>
export default {
  name: "",
  props: ["title","detail"],
  data() {
    return {
      defaultProps: {
        children: "children",
        label: "label"
      },
      types: [],
      dialog: false,
      notifications: false,
      sound: true,
      widgets: false,
      activator: null,
      attach: null,
      colors: ["green", "purple", "indigo", "cyan", "teal", "orange"],
      editing: null,
      index: -1,
      items: [],
      nonce: 1,
      menu: false,
      model: [],
      x: 0,
      search: null,
      y: 0,
      trees: [],
      menu1: false,
      date: null,
      time: null,
      menu2: false,
      timing_btn: true,
      timing: false,
      publish: true,
      password: false,
      publishs: false,
      state: [
        {
          k: 1,
          v: "发布"
        },
        {
          k: 0,
          v: "草稿"
        }
      ],
      publicstate: [
        {
          k: "publish",
          v: "公开"
        },
        {
          k: "hidden",
          v: "隐藏"
        },
        {
          k: "password",
          v: "密码保护"
        },
        {
          k: "private",
          v: "私有"
        }
      ],
      Article: {
        title: "",
        slug: "",
        state: 1,
        metas_id: "",
        publicstate: "publish",
        password: "",
        criticism: "1",
        timing: "",
        is_top: 0,
        markdown: "I \u2764\uFE0F emoji!",
        html: "",
        tags: [],
        user_id: ""
      }
    };
  },
  watch: {
    detail(val){
        this.model = val.tags;
        this.Article.title = val.title;
        this.Article.slug = val.slug;
        this.Article.state = val.state;
        this.Article.metas_id = val.metas_id;
        this.Article.publicstate = val.publicstate;
        this.Article.password = val.password;
        this.Article.criticism = val.criticism;
        this.Article.timing = val.timing;
        this.Article.is_top = val.is_top;
        this.Article.markdown = val.markdown;
        this.Article.html = val.html;
        this.Article.tags = val.tags;
        this.user_id = val.user_id;
    },
    model(val, prev) {
      if (val.length === prev.length) return;

      this.model = val.map(v => {
        if (typeof v === "string") {
          v = {
            text: v,
            color: this.colors[this.nonce - 1]
          };

          this.items.push(v);

          this.nonce++;
        }

        return v;
      });
    }
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      this.$Api.MetasGetAll().then(res => {
        this.types = res.data;
      });
    },
    handleShowModal() {
      this.dialog = true;
    },
    handleCloseModal() {
      this.dialog = false;
    },
    // 公开度为密码时弹出输入密码
    select() {
      if (this.Article.publicstate === "password") {
        this.password = true;
        this.publish = false;
      } else if (this.Article.publicstate === "publish") {
        this.Article.password = "";
        this.password = false;
        this.publish = true;
      } else {
        // 更改公开度时清除密码
        this.Article.password = "";
        this.password = false;
        this.publish = false;
      }
    },
    // 输入密码后验证是否为有效数据 然后关闭
    passwords() {
      this.password = false;
      this.publishs = false;
    },
    // 定时发布
    timings() {
      this.Article.timing = `${this.date} ${this.time}`;
      console.log("TCL: timings -> this.Article.timing", this.Article.timing);
      this.timing = false;
      this.timing_btn = false;
    },
    // 如果移除定时发布,那么时间就为空
    chip() {
      console.info("chip");
      this.Article.timing = ``;
      console.log("TCL: chips -> this.Article.timing", this.Article.timing);
      this.timing = false;
      this.timing_btn = true;
    },
    edit(index, item) {
      if (!this.editing) {
        this.editing = item;
        this.index = index;
      } else {
        this.editing = null;
        this.index = -1;
      }
    },
    filter(item, queryText, itemText) {
      if (item.header) return false;

      const hasValue = val => (val != null ? val : "");

      const text = hasValue(itemText);
      const query = hasValue(queryText);

      return (
        text
          .toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      );
    },
    // 选中的节点
    checktree(d) {
      this.Article.metas_id = d.id;
      console.log("TCL: changetree -> data", d);
    },
    //添加／修改
    submodel() {
        if(this.title == "新增文章"){
            this.Article.tags = this.model;
            this.Article.html = this.$refs.md.d_render;

            console.log("TCL: submodel -> this.Article", this.Article);
            console.info(this.$refs);
            this.$Api.ArticleCreate(this.Article)
            .then(res => {
                // this.dialog = false;
                this.closewindow();
                console.log("TCL: submodel -> res", res);
                this.$parent.init();
            })
            .catch(err => {
                console.log("TCL: CreateMetas -> err", err);
                this.$parent.init();
            });
        }else{
            this.Article.tags = this.model;
            this.Article.html = this.$refs.md.d_render;
            this.$Api.ArticleUpdate({
                id: this.detail.id,
                update: this.Article
            })
            .then(res => {
                // this.dialog = false;
                this.closewindow();
                this.$Message("修改文章成功", {
                    timeout: 3000,
                    icon: "info"
                });
                // console.log("TCL: submodel -> res", res);
                this.$parent.init();
            })
            .catch(err => {
                console.log("TCL: CreateMetas -> err", err);
                this.$parent.init();
            });
        }

    },
    // 上传图片
    $imgAdd(pos, $file) {
      var formdata = new FormData();
      formdata.append("image", $file);
      this.$axios({
        url: "/Article/upload",
        method: "post",
        data: formdata,
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then(url => {
        console.log("TCL: $imgAdd -> url", url);
        this.$refs.md.$img2Url(pos, `${url.data.path}`);
      });
    },
    //清空数据
    closewindow(){
        this.dialog = false;
        this.model = [];
        this.Article= {
            title: "",
            slug: "",
            state: 1,
            metas_id: "",
            publicstate: "publish",
            password: "",
            criticism: "1",
            timing: "",
            is_top: 0,
            markdown: "I \u2764\uFE0F emoji!",
            html: "",
            tags: [],
            user_id: ""
        };
    }
  }
};
</script>

<style>
.v-note-wrapper {
  min-width: 0 !important;
}
</style>
