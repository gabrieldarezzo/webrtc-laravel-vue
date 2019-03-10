<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">My Chat</div>

                    <div class="card-body">
                       <div class="col-md-3">
                            <ul class="nav flex-column">
                                <li class="nav-item" v-for="user in users" :key="user.id">
                                    <a href="#" class="nav-link" @click="loadMessages(user)">{{user.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                users: []
            }
        },
        mounted() {
            axios.get('/users').then((response) => {
                this.users = response.data
            })
        },
        methods: {
            loadMessages(user) {
                console.log(user)
            }
        }
    }
</script>
