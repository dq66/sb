<template>
    <div class="text-xs-center">
        <v-dialog v-model="message" width="500">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                    {{ title }}
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation >
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.title" label="网站标题" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.connect" label="网站链接" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <input @change="fileImage" type="file" accept="image/jpeg,image/x-png,image/gif" style="margin-left: -46%;" />
                                <v-text-field @change="fileImage" type="hidden" v-model="metaspost.avatar"  required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.describe" label="网站描述" required></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="!valid" color="primary" flat @click="CreateMetas">
                        确认
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
  props: ["desse", "detail", "title"],
  data() {
    return {
      loading: false,
      desserts: this.desse,
      message: false,
      dialog: false,
      valid: false,
      metaspost: {
        title: "",
        avatar: "",
        connect: "",
        describe: ""
      }
    };
  },
  watch: {
    desse(val) {
      this.desserts = val;
    },
    detail(val) {
      this.detail = val;
      this.metaspost = val;
    },
    title(val) {
      this.title = val;
    }
  },
  methods: {
    handleShowModal() {
    //   console.info(this);
      this.message = true;
    },
    handleCloseModal() {
      this.message = false;
    },
    //上传图片
    fileImage(e){
        console.log(e);
        var file = e.target.files[0];
        console.log(file);
        var formdata = new FormData();
        formdata.append('file', file,file.name);
        this.axios({
            url: "/links/upload",
            method: "post",
            data: formdata,
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })
        .then(url => {
            console.log("TCL: $imgAdd -> url", url);
            // this.$refs.md.$img2Url(pos, `${url.data.path}`);
        });

    },
    // 添加&修改
    CreateMetas() {
      if (this.$refs.form.validate()) {
        if (this.title === "新增数据") {
            console.log(this.metaspost);
            this.$Api
            .LinksCreate(this.metaspost)
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("添加友情链接成功", {
                timeout: 3000,
                icon: "info"
              });
              this.console.log("TCL: CreateMetas -> res", res);
              this.Scavenging();
              this.$parent.init();
            })
            .catch(err => {
              console.log("TCL: CreateMetas -> err", err);
              this.Scavenging();
              this.$parent.init();
            });
        } else {
            console.log(this.metaspost);
          this.$Api
            .LinksUpdate({
              id: this.detail.id,
              update: this.metaspost
            })
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("修改友情链接成功", {
                timeout: 3000,
                icon: "info"
              });
              console.log("TCL: CreateMetas -> res", res);
              this.Scavenging();
              this.$parent.init();
            })
            .catch(err => {
              console.log("TCL: CreateMetas -> err", err);
              this.Scavenging();
              this.$parent.init();
            });
        }
      }
    },
    //清除数据
    Scavenging(){
        this.metaspost.title = "";
        this.metaspost.avatar = "";
        this.metaspost.connect = "";
        this.metaspost.describe = "";
    }
  },
  mounted() {
    // console.info(this.desserts);
  }
};
</script>
