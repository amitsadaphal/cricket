<template>
    <v-col>
   
        <v-data-table
        item-key="name"
        :loading="loading"
        :headers="headers"
        :items="teams"
        sort-by="name"
        class="elevation-1"
        >
    
        <template v-slot:top>
      <v-toolbar flat color="black">
        <v-toolbar-title>Cricket Team</v-toolbar-title>
        <v-divider
          class="mx-12"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="500px">

          <!-- <template v-slot:activator="{ on }">
            <v-btn color="error" dark class="mb-2" v-on="on">Add New Item</v-btn>
          </template> -->

          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="editedItem.name" label="Team name"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="editedItem.club_state" label="State"></v-text-field>
                  </v-col>                  
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

         <v-dialog v-model="playerdialog" max-width="500px">

          <v-card>
            <v-card-title>
              <span class="headline">Team {{ teameditItem.name }} Players</span>
            </v-card-title>

           <template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
           <th class="text-left">Player Image</th>
          <th class="text-left">First Name</th>
          <th class="text-left">Last Name</th>         
        </tr>
      </thead>
      <tbody>
        <tr v-for="player in playereditItem.players" :key="player.firstName">
         <td><v-img :src="player.imageUri" :alt="player.imageUri" style="width: 50px; height: 50px"></v-img></td>
          <td>{{ player.firstName }}</td>
          <td>{{ player.lastName }}</td>
          
        </tr>
      </tbody>
    </template>
  </v-simple-table>
           </template>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeplayer">OK</v-btn>              
            </v-card-actions>
          </v-card>
        </v-dialog>         

      </v-toolbar>
    </template>

    <template v-slot:item.name="{ item }">            
    <v-list-item :key="item.name" link
    @click="playerItem(item)">{{item.name}}</v-list-item>           
    </template>

      <template v-slot:item.logoUri="{ item }">
            <div class="p-2">
              <v-img :src="item.logoUri" :alt="item.logoUri" style="width: 50px; height: 50px"></v-img>
            </div>
          </template>

    <template v-slot:item.action="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>

         <v-snackbar
              v-model="snackbar"
            >
              Record Deleted Successfully!
              <v-btn
                color="red"
                text
                @click="snackbar = false"
              >
                Close
              </v-btn>
            </v-snackbar>

        </v-data-table>

    </v-col>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      playerdialog: false,
      loading: false,
      snackbar: false,
      headers: [
        {
          text: 'Team Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Team Logo', value: 'logoUri' },
        { text: 'Team Country', value: 'club_state' },        
        { text: 'Actions', value: 'action', sortable: false },
      ],
      teams: [],      
      editedIndex: -1,
      editedItem: {
        name: '',
        club_state: '',           
      },
      teameditedIndex: -1,
      teameditItem: {
        name: '',
      },
      playereditedIndex: -1,      
      playereditItem: {
        firstName: '',
        lastName: '',
        country: '',         
      },
      defaultItem: {
        name: '',
        club_state: '',              
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add Item' : 'Edit Team'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      playerdialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {

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

            axios.get('/api/teams',{})
          .then(res => this.teams = res.data.teams)
          //.catch(res => console.log(res.data.teams[0].players))
          .catch(err => {
              if(err.response.status == 401)
              localStorage.removeItem('token');
              this.$router.push('/login');
          })
          //.then(res => this.teams = console.log(res.data.teams[0].players))
      },
      
      playerItem (item) {
        this.playereditedIndex = this.teams[0].players.indexOf(item)
        this.playereditItem = Object.assign({}, item)

        this.teameditedIndex = this.teams.indexOf(item)
        this.teameditItem = Object.assign({}, item)        
        this.playerdialog = true
      },

      editItem (item) {
        this.editedIndex = this.teams.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.teams.indexOf(item)
        let decide = confirm('Are you sure you want to delete this item?')
        if(decide)
        {
            axios.delete('/api/teams/'+item.id)
          .then(res => {
          this.snackbar = true
          this.teams.splice(index,1)            
          }).catch(err => console.log(err.response))    
        }
            
      },

      closeplayer () {
        this.playerdialog = false
        setTimeout(() => {
          this.playereditItem = Object.assign({}, this.defaultItem)
          this.playereditedIndex = -1
        }, 300)
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {        
        if (this.editedIndex > -1) {
          console.log(this.editedItem.id);
          axios.put('/api/teams/'+ this.editedItem.id, {'name':this.editedItem.name})
          .then(res => Object.assign(this.teams[this.editedIndex], res.data.teams))
          .catch(err => console.log(err.response))          
        } else {
          axios.post('/api/teams',{'name':this.editedItem.name})
          .then(res => this.teams.push(res.data.teams))
          .catch(err => console.dir(err.response))          
        }
        this.close()
      },
    },
  }
</script>
<style scoped></style>
