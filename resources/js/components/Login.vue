<template>
    <v-app id="inspire">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark>
                                <v-toolbar-title>登录到TeeBlog</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-tooltip right>
                                    <v-btn slot="activator" icon large href="https://codepen.io/johnjleider/pen/wyYVVj"
                                        target="_blank">
                                        <v-icon large>mdi-codepen</v-icon>
                                    </v-btn>
                                    <span>Codepen</span>
                                </v-tooltip>
                            </v-toolbar>
                            <v-card-text>
                                <v-form v-model="valid" lazy-validation>
                                    <v-text-field prepend-icon="person" v-model="name" :rules="nameRules" name="login"
                                        label="用户名" type="text"></v-text-field>
                                    <v-text-field id="password" prepend-icon="lock" v-model="password" :rules="passwordRules"
                                        name="password" label="密码" type="password"></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="!valid" @click="login" :loading="loading" dark>戳一下</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
  data: () => ({
    drawer: null,
    loading: false,
    valid: true,
    name: "",
    nameRules: [v => !!v || "请填写用户名"],
    password: "",
    passwordRules: [v => !!v || "请填写密码"]
  }),
  props: {
    source: String
  },
  methods: {
    login() {
      this.loading = true;
      var app = this;
        this.$auth.login({
          params: {
            name: app.name,
            password: app.password
          },
          success: function(response) {
            console.log("TCL: login -> response", response);
            this.$Message("登录成功", {
              timeout: 3000,
              icon: "info"
            });
          },
          error: function(error) {
            this.$Message.error(error.response.data.message);
          },
          rememberMe: true,
          redirect: "/",
          fetchUser: true
        });
      this.loading = false;
    }
  }
};
</script>
