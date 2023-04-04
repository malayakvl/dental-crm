<template>
    <div class="container-fluid">
        <div class="panel blue-panel">
            <h1><i class="fas fa-user-md"></i>  Сотрудники клиники</h1>
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
            <div class="row mt-3">
                <vue-good-table
                    v-if="rows.length > 0"
                    :columns="columns"
                    :rows="rows"
                    :group-options="{
                    enabled: true
                }"
                >
                    <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field === 'name' && props.formattedRow['photo']">
                        {{ props.formattedRow['name'] }}
                    </span>
                    <span v-if="props.column.field === 'photo' && props.formattedRow['photo']">
                       <img :src="`/storage/${props.formattedRow['photo']}`" class="avatar d-block  m-auto" alt="">
                    </span>
                    <span v-if="props.column.field === 'photo' && !props.formattedRow['photo']" class="avatar d-block  m-auto">
                       <i class="fas fa-ghost"></i>
                    </span>
                    <span v-if="props.column.field === 'pivot.invite_date'">
                        {{ getFormat(props.formattedRow[props.column.field]) }}
                    </span>
                    <span v-if="props.column.field === 'pivot.last_visit_date'">
                        {{ getFormat(props.formattedRow[props.column.field]) }}
                    </span>

                    <span v-if="props.column.field === 'name' && !props.formattedRow['photo']">
                        {{ props.formattedRow['name'] }}
                    </span>
                    <span v-if="!['name', 'pivot.invite_date', 'pivot.last_visit_date', 'photo'].includes(props.column.field)">
                        {{props.formattedRow[props.column.field]}}
                    </span>
                    </template>

                </vue-good-table>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import { VueAutosuggest } from 'vue-autosuggest';
import { mapState, mapActions, mapGetters } from "vuex";
import * as staffGetters from "../../store/modules/Staff/types/getters";
import * as staffActions from "../../store/modules/Staff/types/actions"
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";

export default {
    components: {
        VueAutosuggest,
        VueGoodTable
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
            sectionConfigs: {
                'users': {
                    limit: 10,
                    onSelected: selected => {
                        this.selected = selected.item;
                    }
                }
            },
            value: '',
            columns: [
                {
                    label: ' ',
                    field: 'photo',
                },
                {
                    label: ' ',
                    field: 'name',
                },
                {
                    label: 'Дата приглашения в клинику',
                    field: 'pivot.invite_date',
                },
                {
                    label: 'Email',
                    field: 'email',
                },
                {
                    label: 'Телефон',
                    field: 'phone',
                },
                {
                    label: 'Последний визит',
                    field: 'pivot.last_visit_date',
                },
            ]
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
            const rows = []
            values.roles.forEach(role => {
                if (values.staffsByRoles[role]) {
                    rows.push({
                        mode: 'span',
                        label: this.$t(`staff.${role}`),
                        html: false,
                        children: values.staffsByRoles[role]
                    })
                }
            });
            this.rows = rows;
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
            getStaff: staffActions.GET_LIST,
            findStaff: staffActions.FIND_STAFF,
            inviteStaff: staffActions.INVITE_STAFF,
        }),
        getFormat(date) {
            return date ? moment(date).format('DD.MM.YYYY') : '';
        },
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
