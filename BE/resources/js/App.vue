<template>
    <div id="app" class="bg-gray-150">
        <div class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-gray-150 text-black dark:text-white">
            <LeftNav :defaultClinic="clinic" />
<!--            <div class="sidebar">-->
<!--                <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">-->
<!--                    <ul>-->
<!--                        <li>-->
<!--                            <a class="brand" />-->
<!--                        </li>-->
<!--                        <li class="active">-->
<!--                            <a>-->
<!--                                <i class="fas fa-tachometer-alt text-white" />-->
<!--                                <span class="s-caption">Панель управления</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="fas fa-bell text-blue-350" />-->
<!--                                <span class="s-caption">Уведомления</span>-->
<!--                                <em>9</em>-->
<!--                            </a>-->
<!--                        </li>-->

<!--                        <li>-->
<!--                            <div class="separator" />-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="fas fa-clinic-medical text-blue-350" />-->
<!--                                <span class="s-caption">Кабинеты</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="waiting-list" />-->
<!--                                <span class="s-caption">Waiting List</span>-->
<!--                                <em>9</em>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="shipping" />-->
<!--                                <span class="s-caption">Shipping</span>-->
<!--                                <em>10</em>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="buyers" />-->
<!--                                <span class="s-caption">Buyers</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                <i class="payments" />-->
<!--                                <span class="s-caption">Payments</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                    <span class="mb-5 px-5 py-3 hidden md:block text-center text-xs">-->
<!--                        <ul class="nav-footer">-->
<!--                            <li>-->
<!--                                <a href="">About</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="">Cookies</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="">Privacy</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="">Terms</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                        <span class="copyright">@ 2021 Liveproshop</span>-->
<!--                    </span>-->
<!--                </div>-->
<!--            </div>-->
            <div class="h-full mt-8 mb-10 md:ml-80 text-sm mr-10">
                <div class="flex">
                    <div class="w-full sm:w-1/2 md:w-2/3 lg:w-4/5">
                        <form>
                            <div class="relative">
                                <input
                                    class="form-control"
                                    placeholder="Click to Search" />
                                <i class="input-close" />
                            </div>
                        </form>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/5 flex items-center justify-end">
                        <i class="fas fa-bell" />
                        <span class="divider" />
                        <img src="" class="rounded-full" width="24" height="24">
                        <span class="profile-name">
                            Christian Z. Andrew
                            <em>I Dunno Store, Inc</em>
                        </span>
                        <span class="divider" />
                        <span class="mr-5">
                            <i class="fas fa-sign-out-alt" />
                        </span>
                    </div>
                </div>
                <router-view />
            </div>
        </div>
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
