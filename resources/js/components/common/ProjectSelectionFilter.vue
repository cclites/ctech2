<template>
    <b-form-group class="mt-2 mb-1">
        <b-select v-model="project_id" @change="update">
            <option value=""> Select Project </option>
            <option v-for="item in items" :key='item.id' :value="item.id">
                {{ item.name }}
            </option>
        </b-select>
    </b-form-group>
</template>

<script>

    export default {

        props: {
            id: ''
        },

        components: {},

        mixins: [],

        data() {
            return {
                items: [],
                project_id: '',
                url: '/projects'
            }
        },

        computed: {},

        methods: {

            getProjects(){
                axios.get(this.url)
                    .then((response) => {
                        this.items = response.data.projects;
                    }, (error) => {
                        console.log(error);
                    });
            },

            update(){
                this.$emit('filter', this.project_id);
            }
        },

        mounted() {
            this.getProjects();
        },

        watch: {
            id: function(){
                this.project_id = this.id;
            },
        },
    }
</script>

<style scoped>

</style>
