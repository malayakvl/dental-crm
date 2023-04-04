<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-bell"></i>  Мои уведомления</h1>
            <div v-if="rows.length === 0" class="alert alert-primary" role="alert">
                Нет уведомлений
            </div>
            <div class="container">
                <div class="white-block">
                    <div class="row notice-item" v-for="(notice, index) in rows"
                         v-bind:id="`notice-${notice.id}`">
                        <div class="col-lg-9">
                            <div class="dropdown mt-2">
                                <button class="btn dropdown-toggle square-toggle" @click="showDropdownMenu(notice.id)" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-square"></i>
                                </button>
                                <ul class="dropdown-menu notice-dropdown" v-bind:class="{show: showDropdownNotice[notice.id]}">
                                    <li>
                                        <a href="javascript:;" class="dropdown-item" @click="acceptInvitation(notice.id)">
                                            <i class="fas fa-square c-green rounded"></i> Принять
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="dropdown-item" @click="rejectInvitation(notice.id)">
                                            <i class="fas fa-square c-gray rounded"></i> Отклонить
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="dropdown-item" @click="markNotification(notice.id)">
                                            <i class="fas fa-square c-pink rounded"></i> Отметить как прочитанное
                                        </a>
                                    </li>
                                    <li class="selected-gray">
                                        <a href="javascript:;"  class="dropdown-item" @click="deleteNotification(notice.id)">
                                            <i class="fas fa-times"></i> Удалить
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div v-if="notice.clinic" :style="{'background-image': `url(/storage/${notice.clinic.logo})`}" class="avatar float-start mr-2"></div>
                            <div v-if="notice.sender && !notice.clinic" :style="{'background-image': `url(/storage/${notice.sender.photo})`}" class="avatar float-start mr-2"></div>
                            <div class="message">{{ notice.message }}</div>
                        </div>
                        <div class="col-lg-3">
                            <span class="date">{{ notice.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    components: {
    },
    data() {
        return {
            rows: [],
            showDropdownNotice: {},
        }
    },
    mounted() {
        this.getList();
    },
    methods: {
        showDropdownMenu(id) {
            const _showDropdown = this.showDropdownNotice;
            Object.keys(_showDropdown).forEach(key => {
                if (key != id) {
                    _showDropdown[key] = false;
                }
            });
            this.showDropdownNotice = {};
            if (_showDropdown[id] === undefined) {
                _showDropdown[id] = true;
            } else {
                _showDropdown[id] = !_showDropdown[id];
            }
            this.showDropdownNotice = _showDropdown;
        },
        acceptInvitation(id) {
            axios.get(`/api/notifications/accept/${id}`)
                .then(response => response.data)
                .then(response => {
                    this.rows = response.data;
                    this.$store.commit("setNoticeCnt", response.data.length);
                });
        },
        rejectInvitation(id) {
            axios.get(`/api/notifications/reject/${id}`)
                .then(response => response.data)
                .then(response => {
                    this.rows = response.data;
                    this.$store.commit("setNoticeCnt", response.data.length);
                });
        },
        deleteNotification(id) {
            axios.get(`/api/notifications/delete/${id}`)
                .then(response => response.data)
                .then(response => {
                    this.rows = response.data;
                    this.$store.commit("setNoticeCnt", response.data.length);
                });
        },
        markNotification(id) {
            axios.get(`/api/notifications/markRead/${id}`)
                .then(response => response.data)
                .then(response => {
                    this.rows = response.data;
                    this.$store.commit("setNoticeCnt", response.data.length);
                });
        },
        getList() {
            this.rows = [];
            axios.get("/api/notifications")
                .then(response => response.data)
                .then(response => {
                    this.rows = response.data;
                });
        },
        deleteClinic(clinicId) {

        },
        selectionChanged() {

        }
    }
}
</script>
