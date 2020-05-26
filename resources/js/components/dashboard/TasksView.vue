<template>
    <div>
        <b-row>
            <b-card header=""
                    header-text-variant="white"
                    header-bg-variant="info"
            >
                <b-row>
                    <b-col>
                        <b-form-group label="Task Name">
                            <b-input v-model="name"></b-input>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <project-selection class="project-selection" :id="project_id" @filter="updateProjectId"></project-selection>
                    </b-col>

                    <b-col>
                        <b-form-group>
                            <button type="button" v-if="edit" class="btn btn-outline-success" @click="updateTask">Save Edit</button>
                            <button type="button" v-else class="btn btn-outline-success" @click="createTask">Save</button>
                        </b-form-group>
                    </b-col>

                </b-row>

                <hr>

                <b-row>
                    <b-col class="mb-2">
                        <project-selection @filter="filter"></project-selection>
                    </b-col>
                </b-row>

                <b-table striped hover
                         :items="filteredItems"
                         :sort-by="sortBy"
                         :sort-desc="sortDesc"
                         :fields="fields"
                >
                    <template v-slot:cell(actions)="row">
                        <svg class="bi bi-arrow-up-short"
                             width="1em"
                             height="1em"
                             viewBox="0 0 16 16"
                             fill="currentColor"
                             xmlns="http://www.w3.org/2000/svg"
                             @click="moveUp(row)"
                        >
                            <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5z"/>
                            <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8 5.707 5.354 8.354a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>

                        <svg class="bi bi-arrow-down-short"
                             width="1em"
                             height="1em"
                             viewBox="0 0 16 16"
                             fill="currentColor"
                             xmlns="http://www.w3.org/2000/svg"
                             @click="moveDown(row)"
                        >
                            <path fill-rule="evenodd" d="M4.646 7.646a.5.5 0 0 1 .708 0L8 10.293l2.646-2.647a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z"/>
                            <path fill-rule="evenodd" d="M8 4.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5z"/>
                        </svg>

                            <svg @click="editTask"
                                 class="bi bi-pencil text-success"
                                 width="1em"
                                 height="1em"
                                 viewBox="0 0 16 16"
                                 fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
                                <path fill-rule="evenodd" d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                                <path fill-rule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                            </svg>

                            <svg @click="deleteTask(row.item.id)"
                                 class="bi bi-trash text-danger"
                                 width="1em"
                                 height="1em"
                                 viewBox="0 0 16 16"
                                 fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>

                    </template>
                </b-table>

            </b-card>
        </b-row>
    </div>

</template>

<script>

    export default {

        props: {},

        components: {},

        mixins: [],

        data() {
            return {
                project_id: '',
                items: '', //Original items
                filteredItems: this.items,
                item: '',
                name: '',
                row: '',
                priority: '',
                project: '',
                task: '',
                url: '/tasks',
                fields: ['name', 'project', 'created', 'actions'],
                edit: false,
                sortBy: 'priority',
                sortDesc: true
            }
        },

        computed: {},

        methods: {

            getTasks(){

                axios.get(this.url + "?json=1")
                    .then((response) => {
                        this.items = response.data;
                        this.filteredItems = this.items;
                    }, (error) => {
                        console.log(error);
                    });
            },

            createTask(){

                axios.post(this.url, {
                    name: this.name,
                    project_id: this.project_id
                })
                .then((response) => {
                    this.items = response.data;
                    this.filteredItems = this.items;
                    this.resetForm();
                }, (error) => {
                    console.log(error);
                });
            },

            updateTask(){

                axios.patch(this.url + "/" + this.id, {
                    name: this.name,
                    project_id: this.project_id,
                    id : this.id,
                    priority: this.priority
                })
                .then((response) => {
                    this.items = response.data;
                    this.filteredItems = this.items;
                    this.edit = false;
                    this.resetForm();
                }, (error) => {
                    console.log(error);
                });
            },

            deleteTask(id){

                axios.delete(this.url + "/" + id)
                    .then((response) => {
                        this.items = response.data;
                        this.filteredItems = this.items;
                    }, (error) => {
                        console.log(error);
                    });
            },

            editTask(row){

                this.edit = true;
                this.name = row.name;
                this.project_id = row.project_id;
                this.id = row.id;
                this.priority = row.priority;
            },

            moveUp(row){

                this.updatePriority(row.item.id, 1);
            },

            moveDown(row){
                this.updatePriority(row.item.id, -1)
            },

            updatePriority(id, direction){

                axios.patch(this.url + "/priority", {
                    id: id,
                    direction: direction,
                    project_id: this.project_id
                })
                    .then((response) => {
                        this.items = response.data;
                        this.filteredItems = response.data;
                    }, (error) => {
                        console.log(error);
                    });

            },

            updateProjectId(val){
                this.project_id = val;
                this.filter(val);
            },

            filter(val)
            {

                console.log(val);

                if(!val){
                    this.filteredItems = this.items;
                    return;
                }

                this.filteredItems = [];
                this.filteredItems = this.items.filter(function(item){
                    if(item.project_id == val){
                        return item;
                    }
                })
            },

            resetForm(){
                this.name = "";
                this.item = [];
                this.priority = 0;
                this.project_id = "";
            },
        },

        mounted() {
            this.getTasks();
        },

        watch: {
        },
    }
</script>

<style scoped>
    svg{
        cursor: pointer;
    }

    button{
        position: relative;
        top: 30px;
    }

    .project-selection{
        position: relative;
        top: 22px;
    }
</style>
