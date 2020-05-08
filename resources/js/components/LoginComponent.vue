<template>
    <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="error"
                dark
                flat
              >
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer />
                <!-- <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      :href="source"
                      icon
                      large
                      target="_blank"
                      v-on="on"
                    >
                      <v-icon>mdi-code-tags</v-icon>
                    </v-btn>
                  </template>
                  <span>Source</span>
                </v-tooltip> -->
                <!-- <v-tooltip right>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      icon
                      large
                      href="https://codepen.io/johnjleider/pen/pMvGQO"
                      target="_blank"
                      v-on="on"
                    >
                      <v-icon>mdi-codepen</v-icon>
                    </v-btn>
                  </template>
                  <span>Codepen</span>
                </v-tooltip> -->
              </v-toolbar>
              <v-card-text>

                <v-progress-linear
                :active="loading"
                :indeterminate="loading"
                absolute
                top
                color="white accent-4">
                </v-progress-linear>

                <v-form
                  ref="form"
                  v-model="valid"
                >
                  <v-text-field
                    label="Login"
                    name="login"
                    v-model="email"
                    :rules="emailRules"
                    required
                    prepend-icon="mdi-account-lock"
                    type="email"
                  />

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    v-model="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    required
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="error" :disabled="!valid" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>

               <v-snackbar
              v-model="snackbar"
            >
              {{ text }}
              <v-btn
                color="pink"
                text
                @click="snackbar = false"
              >
                Close
              </v-btn>
            </v-snackbar>

          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
export default{

    data(){
      return {
        valid: true,
        email: '',
        emailRules: [
          v => !!v || 'E-mail is required',
          v => /.+@.+\..+/.test(v) ||'E-mail must be valid',
        ],
        password: '',
        passwordRules: [
          v => !!v || 'Password is required',
          
        ],
        loading: false,
        snackbar: false,
        text: ''
      }
    },
    created(){
      this.$vuetify.theme.dark = true;
    },
    methods:{
      login: function(){

          // Add a request interceptor
          axios.interceptors.request.use((config) => {
              this.loading = true;
              return config;
            }, (error) => {
              this.loading = false;
              return Promise.reject(error);
            });

          // Add a response interceptor
          axios.interceptors.response.use((response) => {
              this.loading = false;
              return response;
            },(error) => {
              this.loading = false;
              return Promise.reject(error);
            });
            // email & password request send by axios
            axios.post('/api/login',{'email': this.email,'password': this.password
            })
            .then(res =>{
              localStorage.setItem('token',res.data.token)
              localStorage.setItem('loggedIn',true)
              this.$router.push('/admin')
              .then(res => console.log('LoggedIn Successfully'))
              .catch(err => console.log(err))
            })
            .catch( err => {
              this.text = err.response.data.status;
              this.snackbar = true;
            })
      }
    }

}
</script>
<style scoped></style>
