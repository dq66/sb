<template>
    <v-app id="inspire">
        <v-navigation-drawer class="elevation-6" width="256" :clipped="$vuetify.breakpoint.lgAndUp" v-model="drawer"
            fixed app>
            <v-list subheader v-for="item in items" :key="item.title">
                <v-subheader>{{ item.subheader }}</v-subheader>
                <v-list-tile v-for="children in item.children" :key="children.title" :to="children.router">
                    <v-list-tile-action>
                        <v-icon>{{ children.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title v-text="children.title"></v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar flat :clipped-left="$vuetify.breakpoint.lgAndUp" color="cyan" dark app fixed>
            <v-toolbar-title class="ml-0 pl-3 hidden-sm-and-down">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-sm-and-down">TeeBlog</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>apps</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
            <v-btn icon large>
                <v-avatar size="32px">
                    <img src="http://q1.qlogo.cn/g?b=qq&nk=1982890538&s=100" alt="Vuetify">
                </v-avatar>
            </v-btn>
        </v-toolbar>
        <v-content>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    drawer: null,
    items: [
      {
        subheader: "仪表盘",
        children: [
          {
            title: "首页",
            icon: "home",
            router: ""
          },
          {
            title: "主题选择",
            icon: "palette",
            router: ""
          },
          {
            title: "数据备份",
            icon: "backup",
            router: "/back"
          }
        ]
      },
      {
        subheader: "管理",
        children: [
          {
            title: "管理分类",
            icon: "merge_type",
            router: "/metas"
          },
          {
            title: "管理标签",
            icon: "local_offer",
            router: "/tags"
          },
          {
            title: "管理文章",
            icon: "home",
            router: "/article"
          },
          {
            title: "管理评论",
            icon: "textsms",
            router: "/comment"
          },
          {
            title: "管理文件",
            icon: "folder",
            router: ""
          },
          {
            title: "友情链接",
            icon: "link",
            router: "/links"
          }
        ]
      },
      {
        subheader: "设置",
        children: [
          {
            title: "基本设置",
            icon: "settings",
            router: ""
          },
          {
            title: "个人设置",
            icon: "group",
            router: ""
          },
          {
            title: "评论设置",
            icon: "message",
            router: ""
          },
          {
            title: "伪静态设置",
            icon: "phonelink",
            router: ""
          },
          {
            title: "小程序",
            icon: "android",
            router: ""
          }
        ]
      }
    ]
  }),
  props: {
    source: String
  }
};
</script>
<style>
.v-navigation-drawer__border {
  display: none;
}

a:link {
  /* color: blue; */
  text-decoration: none;
}
</style>
