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
                                <v-text-field v-model="metaspost.content" :label="name" required></v-text-field>
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
  props: ["desse", "detail", "title","name"],
  data() {
    return {
      loading: false,
      desserts: this.desse,
      message: false,
      dialog: false,
      valid: false,
      metaspost: {
        content: ""
      }
    };
  },
  watch: {
    desse(val) {
      this.desserts = val;
    },
    detail(val) {
      this.detail = val;
      this.metaspost.content = val.content;
    },
    title(val) {
      this.title = val;
    },
    name(val){
        this.name = val;
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
    // 编辑&回复
    CreateMetas() {
      if (this.$refs.form.validate()) {
        if (this.title === "编辑评论") {
            this.$Api.CommentUpdate({
                id: this.detail.id,
                update: this.metaspost
            })
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("编辑评论成功", {
                timeout: 3000,
                icon: "info"
              });
              console.log("TCL: CreateMetas -> res", res);
              this.$parent.init();
            })
            .catch(err => {
              this.message = false;
              this.$refs.form.reset();
              console.log("TCL: CreateMetas -> err", err);
              this.$parent.init();
            });
        } else {
            this.$Api.CommentCreate(this.metaspost)
            .then(res => {
              this.message = false;
              this.$refs.form.reset();
              this.$Message("回复评论成功", {
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
    },

  },
  mounted() {
    // console.info(this.desserts);
  }
};
</script>
