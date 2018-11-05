<template>
    <div class="text-xs-center">
        <v-dialog v-model="message" width="500">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                    {{ title }}
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.label" :rules="labelRules" label="分类名" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.slug" label="分类别名" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex xs12 sm6 d-flex>
                                <v-select v-model="metaspost.parent" :items="desserts" item-text="label" item-value="id"
                                    label="父级分类 [不选默认为顶级分类]"></v-select>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.order" label="排序" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout wrap>
                            <v-flex>
                                <v-text-field v-model="metaspost.description" label="描述" required></v-text-field>
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
      labelRules: [v => !!v || "分类名必须填写"],
      metaspost: {
        label: "",
        slug: "",
        parent: 0,
        order: "",
        description: ""
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
  created() {
    // console.info(this.desserts);
  },
  methods: {
    handleShowModal() {
    //   console.info(this);
      this.message = true;
    },
    handleCloseModal() {
      this.message = false;
    },
    // 添加&修改
    CreateMetas() {
      if (this.$refs.form.validate()) {
        if (this.title === "新增数据") {
          this.$Api
            .CreateMetas(this.metaspost)
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("添加分类成功", {
                timeout: 3000,
                icon: "info"
              });
              this.console.log("TCL: CreateMetas -> res", res);
              this.$parent.init();
            })
            .catch(err => {
              console.log("TCL: CreateMetas -> err", err);
              this.$parent.init();
            });
        } else {
          this.$Api
            .UpdateMetas({
              id: this.detail.id,
              update: this.metaspost
            })
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("修改分类成功", {
                timeout: 3000,
                icon: "info"
              });
              console.log("TCL: CreateMetas -> res", res);
              this.$parent.init();
            })
            .catch(err => {
              console.log("TCL: CreateMetas -> err", err);
              this.$parent.init();
            });
        }
      }
    }
  },
  mounted() {
    // console.info(this.desserts);
  }
};
</script>
