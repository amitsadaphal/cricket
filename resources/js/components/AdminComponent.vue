<template>
    <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
    >
      <v-list dense>
        <v-list-item
          v-for="item in items"
          :key="item.text"
          link
          :to="item.action"
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              {{ item.text }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <!-- <v-subheader class="mt-4 grey--text text--darken-1">SUBSCRIPTIONS</v-subheader> -->

       <!-- <v-list>
          <v-list-item
            v-for="item in items2"
            :key="item.text"
            link
          >
            <v-list-item-avatar>
              <img
                :src="`https://randomuser.me/api/portraits/men/${item.picture}.jpg`"
                alt=""
              >
            </v-list-item-avatar>
            <v-list-item-title v-text="item.text" />
          </v-list-item>
        </v-list>

         <v-list-item class="mt-4" link>
          <v-list-item-action>
            <v-icon color="grey darken-1">mdi-plus-circle-outline</v-icon>
          </v-list-item-action>
          <v-list-item-title class="grey--text text--darken-1">Browse Channels</v-list-item-title>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon color="grey darken-1">mdi-settings</v-icon>
          </v-list-item-action>
          <v-list-item-title class="grey--text text--darken-1">Manage Subscriptions</v-list-item-title>
        </v-list-item> -->
     
      <v-list-item link @click = "logout">
          <v-list-item-action>
            <v-icon color="grey darken-1">mdi-settings</v-icon>
          </v-list-item-action>
          <v-list-item-title class="--text text--darken-1">Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
      color="red"
      dense
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />

      <v-btn text to="/admin" left>
      <v-icon class="mx-4">fab fa-youtube</v-icon>
      
        <v-list-item-title>Cricket</v-list-item-title>
     
      </v-btn>

      <v-spacer />

      <!-- <v-row> 
        <v-text-field
          :append-icon-cb="() => {}"
          placeholder="Search..."
          single-line
          append-icon="mdi-feature-search-outline"
          color="white"
          hide-details
        />
      </v-row> -->

    </v-app-bar>

    <v-content>
      <v-container>
       
        <v-row>
       <router-view></router-view>

        <v-snackbar
              v-model="snackbar"
            >
              You have Successfully LoggedIn
              <v-btn
                color="red"
                text
                @click="snackbar = false"
              >
                Close
              </v-btn>
            </v-snackbar>

          <v-col class="shrink">
            <!-- <v-tooltip right>
              <template v-slot:activator="{ on }">
                <v-btn
                  :href="source"
                  icon
                  large
                  target="_blank"
                  v-on="on"
                >
                  <v-icon large>mdi-code-tags</v-icon>
                </v-btn>
              </template>
              <span>Source</span>
            </v-tooltip> -->
            <!-- <v-tooltip right>
              <template v-slot:activator="{ on }">
                <v-btn
                  icon
                  large
                  href="https://codepen.io/johnjleider/pen/aezMOO"
                  target="_blank"
                  v-on="on"
                >
                  <v-icon large>mdi-codepen</v-icon>
                </v-btn>
              </template>
              <span>Codepen</span>
            </v-tooltip> -->
          </v-col>
        </v-row>
       
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
export default{
    props: {
      source: String      
    },
    data: () => ({
      snackbar: false,
      drawer: null,      
      items: [
        { icon: 'mdi-account-group', text: 'Teams List', action: '/admin/teams' },
        { icon: 'mdi-account-details', text: 'Point List', action: '/admin/points' },
        { icon: 'mdi-history', text: 'Match Fixes', action: '/admin/matches' },
        // { icon: 'mdi-history', text: 'History' },
        // { icon: 'mdi-playlist-play', text: 'Playlists' },
        // { icon: 'mdi-youtube-tv', text: 'Watch Later' },
      ],
      // items2: [
      //   { picture: 28, text: 'Joseph' },
      //   { picture: 38, text: 'Apple' },
      //   { picture: 48, text: 'Xbox Ahoy' },
      //   { picture: 58, text: 'Nokia' },
      //   { picture: 78, text: 'MKBHD' },
      // ],
    }),
    created () {
      this.$vuetify.theme.dark = true;      
    },
    mounted(){
      this.snackbar = localStorage.getItem('loggedIn') ? true : false;
      localStorage.removeItem('loggedIn');
    },
    methods:{
      logout: function(){
        localStorage.removeItem('token');
        this.$router.push('/login')
        .then(res => {
          this.text = 'You are Logged Out Successfully';
          this.snackbar = true;
        })       
        .catch(err => console.log(err))
      }
    }
}
</script>
<style scoped></style>