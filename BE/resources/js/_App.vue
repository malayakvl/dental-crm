<template>
    <div id="app" class="bg-gray-250">
        <LeftNav :defaultClinic="clinic" />
        <div class="float-start right-container " id="demo">
            <div class="top-nav">
                <div class="float-start" v-if="clinic">
                    <img :src="`/storage/${clinic.logo}`" class="logo">
                    <span class="name">{{ clinic.name }}</span>
                    <b-button v-b-modal.modal-patient class="btn btn-warning violet">Добавить запись</b-button>
                </div>
                <div class="float-end">
                    <div class="hidden sm:flex sm:items-center sm:ml-6 float-end">
                        <div class="dropdown mt-2">
                            <button @click="showMenu" class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ user && user.email }}
                            </button>
                            <ul class="dropdown-menu" v-bind:class="{show: showDropdown}" aria-labelledby="dropdownMenuButton1">
                                <li><router-link :to="{path: `/profile`}" class="dropdown-item" title="Просмотр уведомлений">Профиль</router-link></li>
                                <li><a class="dropdown-item" href="javascript:;" @click="logoutClinic">Выйти из клиники</a></li>
                                <li><a class="dropdown-item" href="javascript:;" @click="logout">Выйти</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="float-end position-relative notification">
                        <router-link :to="{path: `/notifications`}" title="Просмотр уведомлений">
                        <i class="fas fa-bell"></i>
                        <span v-if="noticeCnt" class="absolute bg-pink-350 inline-flex items-center justify-center px-1 py-0.5 text-white not-italic
                                mr-2 text-xs font-bold leading-none rounded-full sright-0">
                            {{noticeCnt}}
                        </span>
                        </router-link>
                    </div>
                </div>
            </div>

            <router-view />
        </div>

        <b-modal id="modal-patient" hide-footer title="Новая запись">
            <PatientForm />
        </b-modal>
    </div>
</template>

<script>
import LeftNav from "./components/menu/LeftNav";
import PatientForm from './components/scheduler/Form.vue';

import { mapState, mapMutations, mapActions } from "vuex";
export default {
    name: "App",
    components: { LeftNav, PatientForm },
    computed: {
        ...mapState(["user", "clinic"]),
        noticeCnt () {
            return this.$store.state.noticeCnt
        }
    },
    mounted() {
        this.loadUser();
        this.checkNotification()
    },
    data() {
        return {
            cntNotifications: 0,
            showDropdown: false
        }
    },
    methods: {
        ...mapMutations(["setUser", "setClinic"]),
        ...mapActions(["loadUser"]),
        showMenu() {
            this.showDropdown = !this.showDropdown;
        },
        logoutClinic() {

        },
        async logout() {
            let formData = new FormData();
            axios.post( `/logout`,
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                ).then(function(response){
                    window.location.href = '/login';
                })
                .catch(e => {
                    console.log('FAILURE!!', e.response);
                    if (e.response) {
                        const errors = e.response.data.errors;
                        this.errors = errors;
                    }
                });

            // await fetch("/auth/logout");
            // this.setUser(null);
            this.$router.push("signup");
        },
        async checkNotification() {
            axios.get(`/api/user/check-notifications`)
                .then(response => response.data)
                .then(response => {
                    this.cntNotifications = response;
                    this.$store.commit("setNoticeCnt", response);
                });
        }
    }
}
</script>
