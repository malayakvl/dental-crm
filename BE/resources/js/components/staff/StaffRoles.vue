<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-user-md"></i>  Роли</h1>
            <div v-if="rows.length === 0" class="alert alert-primary" role="alert">
                У вас нет еще сотрудников
            </div>
            <div v-if="inviteSuccess" class="alert alert-success" role="alert">
                Приглашение было отправлено
            </div>
            <div class="row">
                <div class="col-6">
                    <vue-autosuggest
                        :suggestions="suggestions"
                        :sectionConfigs="sectionConfigs"
                        :input-props="{id:'autosuggest__input', placeholder:'Введите email'}"
                        @input="fetchResults"
                        :renderSuggestion="renderSuggestion"
                        :getSuggestionValue="getSuggestionValue"
                    >
                        <template slot-scope="{suggestion}">
                            <span class="my-suggestion-item">{{suggestion.item}}</span>
                        </template>
                    </vue-autosuggest>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-warning pink" @click="invite">Пригласить</button>
                </div>
            </div>
            <table class="table table-hover table-staff" v-if="rows.length > 0">
                <thead>
                <tr>
                    <th></th>
                    <th>
                        <div v-if="owner.photo" :style="{'background-image': `url(/storage/${owner.photo})`}" class="avatar d-block m-auto"></div>
                        <div v-if="!owner.photo" class="avatar d-block  m-auto"><i class="fas fa-ghost"></i></div>
                        <br/><span class="d-block user-name m-auto">{{owner.name}}</span>
                    </th>
                    <th v-for="(row, index) in rows" v-bind:id="`user-${row.id}`">
                        <div v-if="row.photo" :style="{'background-image': `url(/storage/${row.photo})`}" class="avatar d-block m-auto"></div>
                        <div v-if="!row.photo" class="avatar d-block  m-auto"><i class="fas fa-ghost"></i></div>
                        <br/><span class="d-block user-name m-auto">{{row.name}}</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td>
                        <div class="dropdown mt-2">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Руководитель
                            </button>
                        </div>
                    </td>
                    <td v-for="(row, index) in rows" v-bind:id="`role-${row.id}`">
                        <div class="dropdown mt-2">
                            <button class="btn dropdown-toggle" @click="showDropdownMenu(row.id)" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ roles[row.id] ? $t(`staff.${roles[row.id]}`) : 'Установите роль'}}
                            </button>
                            <ul class="dropdown-menu notice-dropdown" v-bind:class="{show: showDropdownNotice[row.id]}">
                                <li>
                                    <a href="javascript:;" @click="changeRole(row.id, 'ceo')" class="dropdown-item">
                                        Руководитель
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" @click="changeRole(row.id, 'administrator')" class="dropdown-item">
                                        Администратор
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" @click="changeRole(row.id, 'doctor')" class="dropdown-item">
                                        Врач
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" @click="changeRole(row.id, 'assistent')" class="dropdown-item">
                                        Ассистент
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Цвет</td>
                    <td></td>
                    <td v-for="(row, index) in rows" v-bind:id="`color-${row.id}`">
                        <color-picker
                            v-model="color[row.id]"
                            :position="{left: 0, top: '40px'}"
                            @change="change(row.id)"
                            @afterChange="afterChange(row.id)">
                        </color-picker>
                    </td>
                </tr>
                <tr>
                    <td :colspan="rows.length + 2" class="r-gray">Общие настройки</td>
                </tr>
                <tr v-for="permission in permissions" v-bind:id="`color-${permission.id}`">
                    <td>{{permission.title}}</td>
                    <td class="permission-col"><i class="fas fa-check c-green" /></td>
                    <td class="permission-col" v-for="(row, index) in rows" v-bind:id="`color-${row.id}`" @click="changePermission(row.id, permission.id)">
                        <i
                            v-bind:class="{'fas fa-check c-green' : permissionByRole[row.id][permission.id], 'fas fa-ban c-gray': !permissionByRole[row.id][permission.id]}">
                        </i>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import { VueAutosuggest } from 'vue-autosuggest';
import { mapState, mapActions, mapGetters } from "vuex";
import * as staffGetters from "../../store/modules/Staff/types/getters";
import * as staffActions from "../../store/modules/Staff/types/actions"

export default {
    components: {
        VueAutosuggest,
    },
    data() {
        return {
            inviteSuccess: false,
            color: {},
            roles:{},
            timeout: null,
            debounceMilliseconds: 3000,
            results: [],
            selected: null,
            suggestions: [],
            rows:[],
            owner: {},
            permissions: [],
            permissionByRole: [],
            showDropdownNotice: {},
            sectionConfigs: {
                'users': {
                    limit: 10,
                    onSelected: selected => {
                        this.selected = selected.item;
                    }
                }
            },
            value: '',
        }
    },
    computed: {
        ...mapState(["clinicId", "user"]),
        ...mapGetters("Staff", {
            staff: staffGetters.GET_STAFF,
            users: staffGetters.GET_USERS,
            saved: staffGetters.DATA_SAVED
        }),
    },
    watch: {
        staff(values) {
            this.color = {};
            this.roles = {};
            this.owner = values.owner;
            this.rows = values.staff;
            this.permissionByRole = values.permissionByRoles;
            this.permissions = values.permissions;
            const _colors = {};
            const _roles = {}
            values.staff.forEach(staff => {
                _colors[staff.id] = staff.pivot.color ? staff.pivot.color : '#ff7fab';
                _roles[staff.id] = staff.pivot.role;
            })
            this.color = _colors;
            this.roles = _roles;
        },
        users(values) {
            this.suggestions = [];
            this.suggestions.push({ name: "users", data: values });
        },
        saved(value) {
            if (value) {
                this.inviteSuccess = true;
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.inviteSuccess = false;
                }, this.debounceMilliseconds);
            }
        }
    },
    mounted() {
        this.getStaff();
    },
    methods: {
        ...mapActions("Staff", {
            getStaff: staffActions.GET_STAFF,
            setColor: staffActions.SET_COLOR,
            setPermission: staffActions.SET_PERMISSION,
            setRole: staffActions.SET_ROLE,
            findStaff: staffActions.FIND_STAFF,
            inviteStaff: staffActions.INVITE_STAFF,
        }),
        change(id) {
            this.setColor({color: this.color[id].replace('#', ''), id: id});
        },
        changePermission(id, permissionId) {
            this.setPermission({permissionId:permissionId, id: id});
        },
        changeRole(id, role) {
            this.setRole({role: role, id: id});
            this.roles[id] = role;
            this.showDropdownNotice[id] = false;
        },
        showDropdownMenu(id) {
            const _showDropdown = this.showDropdownNotice;
            this.showDropdownNotice = {};
            Object.keys(_showDropdown).forEach(key => {
                if (key != id) {
                    _showDropdown[key] = false;
                }
            });

            if (_showDropdown[id] === undefined) {
                _showDropdown[id] = true;
            } else {
                _showDropdown[id] = !_showDropdown[id];
            }
            this.showDropdownNotice = _showDropdown;
        },
        fetchResults(text) {
            if (text === '' || text === undefined) {
                return;
            }
            if (text.length < 4) {
                return;
            }
            this.findStaff(text);
        },
        renderSuggestion(suggestion) {
            return suggestion.item.email;
        },
        getSuggestionValue(suggestion) {
            let { item } = suggestion;
            return item.email;
        },
        invite() {
            this.inviteStaff(this.selected);
        },
    }
}
</script>
