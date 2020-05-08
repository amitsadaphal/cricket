<template>
    <v-col>
   
        <v-data-table
        item-key="name"
        :loading="loading"
        :headers="headers"
        :items="points"
        sort-by="name"
        class="elevation-1"
        >
    
        <template v-slot:top>
      <v-toolbar flat color="black">
        <v-toolbar-title>Team Points</v-toolbar-title>
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
                    <v-text-field v-model="editedItem.totalPoint" label="Team Point"></v-text-field>
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

      </v-toolbar>
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

    </v-data-table>

    </v-col>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,     
      loading: false,
      headers: [
        {
          text: 'Team Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Team Point', value: 'totalPoint' },            
        // { text: 'Actions', value: 'action', sortable: false },
      ],
      points: [],      
      editedIndex: -1,
      editedItem: {
        name: '',
        totalPoint: '',           
      },
      
      defaultItem: {
        name: '',
        totalPoint: '',              
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add Team Point' : 'Edit Team Point'
      },
    },

    watch: {
      dialog (val) {
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

            axios.get('/api/points',{})
          .then(res => this.points = res.data.points)
          //.catch(res => console.log(res.data.teams[0].players))
          .catch(err => {
              if(err.response.status == 401)
              localStorage.removeItem('token');
              this.$router.push('/login');
          })
          //.then(res => this.teams = console.log(res.data.teams[0].players))
      },

      editItem (item) {
        this.editedIndex = this.points.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.points.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.points.splice(index, 1)
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
          Object.assign(this.points[this.editedIndex], this.editedItem)
        } else {
          this.points.push(this.editedItem)
        }
        this.close()
      },
    },
  }
</script>
<style scoped></style>
