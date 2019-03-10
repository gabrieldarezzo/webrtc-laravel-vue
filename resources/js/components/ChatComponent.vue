<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">My Chat {{started}}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="nav flex-column">
                                    <li class="nav-item" v-for="user in users" :key="user.id">
                                        <a href="#" class="nav-link" @click="loadMessages(user)">{{user.name}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9" v-if="!started">
                                <div class="card bg-warning">
                                    <div class="card-header">
                                        Falando com o <strong>{{currentChat.name}}</strong>
                                    </div>
                                </div>
                                <div class="card bg-info" v-if="messages.length == 0">
                                    <div class="card-header">
                                        Nenhuma mensagem para ouvir
                                    </div>
                                </div>
                                <div 
                                    class="card" 
                                    :class="{'bg-success' : currentChat.id == message.sender}" 
                                    v-for="message in messages" :key="message.id" 
                                    >
                                    <div class="card-header">
                                        ...
                                    </div>

                                </div>
                                <a  :class="{'recording': recording}" @click="rec()" href="#" id="rec"></a>
                            </div>
                            <div class="col-md-9" v-if="started">
                                <div class="card bg-success text-white " v-if="!loading">
                                    <div class="card-header">
                                        Escolha alguém para conversar...
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Selecione alguém da lista ao lado</h5>
                                        <p class="card-text"></p>
                                    </div>
                                </div>
                                <div class="card bg-info text-white " v-if="loading">
                                    <div class="card-header">
                                        Obtendo conversa...
                                    </div>
                                </div>
                                <div class="card bg-danger text-white " v-if="error">
                                    <div class="card-header">
                                        Erro ao carregar a conversa...
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Success card title</h5>
                                        <p class="card-text">Aguarde.</p>
                                    </div>
                                </div>
                            </div>
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
                users: [],
                started: true,
                loading: false,
                error: false,
                currentChat: {},
                messages: {},
                recording: false,                
                recorder: null,  
                recordedData: [],  
                
                
            }
        },
        mounted() {
            axios.get('/users')
            .then((response) => {
                this.users = response.data
            })
            .catch((err) => {
                // this.users = err
                this.error = true
            })
        },
        methods: {
            loadMessages(user) {
                this.started = true
                this.loading = true

                axios.get('/messages/' + user.id)
                .then((response) => {
                    this.started = false
                    this.loading = false
                    this.currentChat = user
                    this.messages = response.data

                    
                })
                .catch((err) => {
                    // this.users = err
                    this.error = true
                    this.loading = false
                })
            },
            rec() {
                this.recording = !this.recording
                if(this.recording) {
                    this.startRec()
                } else {
                    this.stopRec()
                }
            },
            startRec() {
                console.log('Start Rec');
                
                let configRec = {
                    audio : true,
                    video : false,
                };

                const successCallback = (stream) => {
                    this.recorder = new MediaRecorder(stream);
                    
                    this.recorder.ondataavailable = (e) => {
                        this.recordedData.push(e.data);
                        console.log(this.recordedData);
                    }
                    
                    this.recorder.onstop = () => {
                        console.log('Parou de gravar')
                        let blob = new Blob(this.recordedData, {type: 'video/webm'});
                        this.recorder = null;
                        this.recordedData = [];
                        
                        let formData = new FormData();
                        formData.append('audio', blob);
                        formData.append('receiver', this.currentChat.id);

                        axios.post('messages', formData)
                            .then(() => {
                                console.log('send to backend');
                            })

                    }
                    
                    this.recorder.start();
                }

                const errorCallback = (err) => {
                    console.log(err);
                };

                navigator.getUserMedia(configRec, successCallback, errorCallback);
            },
            stopRec() {
                console.log('Stop Rec');
                this.recorder.stop();
            },
        }
    }
</script>

<style>
@keyframes pulse {
    50% {
        background: transparent;
    }
}

a {
    cursor: pointer;
}

#rec {
    position: fixed;
    right: 10px;
    bottom: 10px;
}

#rec:after {
    display: block;
    content: '';
    width: 30px;
    height: 30px;
    background: red;
    border-radius: 30px;
}

#rec.recording:after {
    animation: pulse 1s infinite;
} 
</style>