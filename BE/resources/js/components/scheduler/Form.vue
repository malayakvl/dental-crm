<template>
    <div>
        <div class="scheduler-status row mb-3">
            <div class="dropdown">
                <button class="btn dropdown-toggle square-toggle" @click="showDropdownMenu(1)" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-square" v-bind:style="`color:${color}`"></i>{{ $t(`statuses.${status}`) }}
                </button>
                <ul class="dropdown-menu square-dropdown" v-bind:class="{show: showDropdownNotice[1]}">
                    <li v-for="status in statuses">
                        <a href="javascript:;" class="dropdown-item" @click="setupStatus(status)">
                            <i class="fas fa-square rounded" v-bind:style="`color:${status.color}`"></i> {{ $t(`statuses.${status.name}`) }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <a-form-item label="Пациент">
            <a-input-group>
                <a-auto-complete
                    v-model="patient"
                    :dataSource="dataSource"
                    @search="onSearch"
                    @select="onSelect"
                    class="w-75" />
                <a-button class="ml-1" @click="showPatientForm">+</a-button>
                <div class="error" v-if="errorsData.patient">{{errorsData.patient[0]}}</div>
            </a-input-group>
        </a-form-item>
        <div class="patient-add-form mb-2" v-if="addUser">
            <div class="row">
                <div class="col-6 col-lg-6">
                    <a-form-item label="Фамилия">
                        <a-input v-model.trim="$v.firstName.$model"></a-input>
                        <div class="error" v-if="!$v.firstName.required && submitStatus === 'ERROR'">Обязательное поле</div>
                        <div class="error" v-if="!$v.firstName.minLength">Не менее {{$v.firstName.$params.minLength.min}} символов</div>
                        <div class="error" v-if="errorsData.firstName">{{errorsData.firstName[0]}}</div>
                    </a-form-item>
                </div>
                <div class="col-6 col-lg-6">
                    <a-form-item label="Имя">
                        <a-input v-model.trim="$v.lastName.$model"></a-input>
                        <div class="error" v-if="!$v.lastName.required && submitStatus === 'ERROR'">Обязательное поле</div>
                        <div class="error" v-if="!$v.lastName.minLength">Не менее {{$v.lastName.$params.minLength.min}} символов</div>
                        <div class="error" v-if="errorsData.lastName">{{errorsData.lastName[0]}}</div>
                    </a-form-item>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-lg-6">
                    <a-form-item label="Телефон">
                        <the-mask class="form-control" :mask="['+380(##) ##-##-###']" v-model.trim="$v.phone.$model" />
                        <div class="error" v-if="!$v.phone.required && submitStatus === 'ERROR'">Обязательное поле</div>
                    </a-form-item>
                </div>
                <div class="col-6 col-lg-6">
                    <a-form-item label="Пол">
                        <a-radio-group v-model="gender">
                            <a-radio-button value="m">
                                Муж
                            </a-radio-button>
                            <a-radio-button value="f">
                                Жен
                            </a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12">
                <label class="form-label required">Врач</label>
                <v-select
                    v-model.trim="$v.doctorSelected.$model"
                    class="w-100"
                    :options="doctors"
                    label="name"
                    :selectable="(option) => option.id"
                >
                    <template v-slot:option="option">
                        <span class="select-opt" v-if="!option.id">{{ option.name }}</span>
                        <span v-if="option.id">{{ option.name }}</span>
                    </template>
                </v-select>
                <div class="error" v-if="!$v.doctorSelected.required && submitStatus === 'ERROR'">Обязательное поле</div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12">
                <label class="form-label required">Кабинет</label>
                <v-select
                    v-model.trim="$v.cabinetSelected.$model"
                    class="w-100"
                    :options="cabinetList"
                    label="name"
                />
                <div class="error" v-if="!$v.cabinetSelected.required && submitStatus === 'ERROR'">Обязательное поле</div>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12">
                <label class="form-label required">Время</label>
                <div class="row">
                    <div class="col-4">
                        <a-date-picker v-model.trim="$v.dateSelected.$model" format="DD.MM.YYYY" />
                        <div class="error" v-if="!$v.dateSelected.required && submitStatus === 'ERROR'">Обязательное поле</div>
                    </div>
                    <div class="col-4">
                        <a-time-picker v-model.trim="$v.timeSelectedFrom.$model" format="HH:mm" />
                        <div class="error" v-if="!$v.timeSelectedFrom.required && submitStatus === 'ERROR'">Обязательное поле</div>
                    </div>
                    <div class="col-4">
                        <a-time-picker v-model.trim="$v.timeSelectedTo.$model" format="HH:mm" />
                        <div class="error" v-if="!$v.timeSelectedTo.required && submitStatus === 'ERROR'">Обязательное поле</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12">
                <label class="form-label">Комментарий</label>
                <a-textarea type="text" class="form-control" v-model="comment" />
            </div>
        </div>
        <div class="float-end">
            <b-button class="mt-3 pink-btn" block @click="saveEvent">Сохранить</b-button>
            <b-button class="mt-3" block @click="$bvModal.hide('modal-patient-scheduler')">Закрыть</b-button>
        </div>
    </div>
</template>
<script>
import { required, minLength } from 'vuelidate/lib/validators';
import { mapActions, mapGetters } from "vuex";
import * as cabinetActions from "../../store/modules/Cabinet/types/actions"
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import * as schedulerGetters from "../../store/modules/Scheduler/types/getters";
import * as staffGetters from "../../store/modules/Staff/types/getters";
import * as staffActions from "../../store/modules/Staff/types/actions"
import * as schedulerActions from "../../store/modules/Scheduler/types/actions"
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import moment from "moment";
import { TheMask } from 'vue-the-mask'

export default {
    name: "PatientForm",
    components: {
        'v-select': vSelect,
        TheMask
    },
    props: [],
    validations() {
        if (!this.addUser) {
            return {
                cabinetSelected: {
                    required,
                },
                doctorSelected: {
                    required,
                },
                dateSelected: {
                    required
                },
                timeSelectedFrom: {
                    required
                },
                timeSelectedTo: {
                    required
                },
            }
        } else {
            return {
                cabinetSelected: {
                    required,
                },
                doctorSelected: {
                    required,
                },
                dateSelected: {
                    required
                },
                timeSelectedFrom: {
                    required
                },
                timeSelectedTo: {
                    required
                },
                firstName: {
                    required,
                    minLength: minLength(4)
                },
                lastName: {
                    required,
                    minLength: minLength(4)
                },
                phone: {
                    required,
                },
            }
        }
    },
    data() {
        return {
            uploadError: '',
            submitStatus: null,
            errorsData: {},
            doctors:[],
            dataSource: [],
            dataSourceReal: [],
            doctorSelected: null,
            cabinetSelected: null,
            timeSelectedFrom: moment(),
            timeSelectedTo: moment(),
            dateSelected: moment(),
            patient: null,
            patientId: null,
            gender: null,
            firstName: null,
            lastName: null,
            phone: null,
            comment: null,
            addUser: false,
            showDropdownNotice: {},
            status: "planned",
            statuses: [
                {name: "planned", color: "#4c95f5"},
                {name: "confirm", color: "#eb9d17"},
                {name: "done", color: "#7d17eb"},
                {name: "missed", color: "#fae73c"},
                {name: "postponed", color: "#3cfafa"},
                {name: "noanswer", color: "#ff5722"},
                {name: "late", color: "#ff21ed"},
                {name: "inclicnic", color: "#2971ba"},
                {name: "incabinet", color: "#37ff21"},
                {name: "decline", color: "#222223"}
            ],
            color: "#4c95f5",
        }
    },
    mounted() {
        this.loadSelectors();
    },
    computed: {
        ...mapGetters("Cabinet", {
            cabinetList: cabinetGetters.GET_MODAL_CABINETS,
        }),
        ...mapGetters("Scheduler", {
            cabinetId: schedulerGetters.GET_CABINET,
            date: schedulerGetters.GET_DATE,
        }),
        ...mapGetters("Staff", {
            staff: staffGetters.GET_STAFF,
            patients: staffGetters.GET_PATIENTS,
        }),
    },
    watch: {
        errors(value) {
            console.log(value);
        },
        saved(value) {
            this.setSaved(false);
        },
        staff(values) {
            const _staff = [];
            values.roles.forEach(role => {
                if (values.staffsByRoles[role]) {
                    _staff.push({id:null, name: this.$t(`staff.${role}`)});
                    values.staffsByRoles[role].forEach(staff => {
                        _staff.push(staff);
                    });
                }
            });
            this.doctors = _staff;
        },
        patients(values) {
            this.dataSource = [];
            this.dataSourceReal = [];
            const patients = [];
            values.forEach(patient => {
                patients.push(`${patient.last_name} ${patient.first_name} ${patient.middle_name ? patient.middle_name : ''}`);
            });
            this.dataSource = patients;
            this.dataSourceReal = values;
        },
        date(value) {
            this.timeSelectedFrom = moment(value, 'YYYY-MM-DD');
            this.timeSelectedTo = moment(value, 'YYYY-MM-DD').add(1, 'hours');
            this.dateSelected = moment(value, 'DD.MM.YYYY');
        },
        cabinetList(values) {
            if (values.length > 0) {
                if (this.cabinetId) {
                    const selected = values.filter(cabinet => cabinet.id === this.cabinetId)
                    if (selected.length > 0) {
                        this.cabinetSelected = selected[0];
                    }
                }
            }
        }
    },
    methods: {
        ...mapActions("Cabinet", {
            getCabinets: cabinetActions.GET_MODAL_CABINETS,
        }),
        ...mapActions("Scheduler", {
            addEvent: schedulerActions.ADD_EVENT,
        }),
        ...mapActions("Staff", {
            getStaff: staffActions.GET_LIST,
            getPatients: staffActions.GET_PATIENTS
        }),
        setupStatus(status) {
            this.status = status.name;
            this.color = status.color;
            this.showDropdownNotice[1] = false;
        },
        showPatientForm() {
          this.addUser = !this.addUser;
            this.errorsData = {};
        },
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
        loadSelectors() {
            this.getStaff();
            this.getCabinets();
            if (this.date) {
                const date = moment(this.date, 'YYYY-MM-DD');
                const dateTo = moment(this.date, 'YYYY-MM-DD').add(1, 'hours');
                this.timeSelectedFrom = moment(this.date, 'YYYY-MM-DD');
                this.timeSelectedTo = moment(this.date, 'YYYY-MM-DD').add(1, 'hours');
                this.dateSelected = moment(this.date, 'DD.MM.YYYY');
            }
        },
        onSearch(searchText) {
            this.getPatients(searchText);
        },
        onSelect(value) {
            const _patient = this.dataSourceReal.find(patient => `${patient.last_name} ${patient.first_name} ${patient.middle_name ? patient.middle_name : ''}` === value);
            this.patientId = _patient.id;
        },
        saveEvent() {
            this.errorsData = {};
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                const errors = {};
                if (!this.addUser && !this.patient) {
                    errors['patient'] = ['Обязательное поле'];
                }
                if (Object.keys(errors) > 0) {
                    this.errorsData = errors;
                } else {
                    let formData = new FormData();
                    formData.append('patient_id', this.patientId);
                    formData.append('doctor_id', this.doctorSelected.id);
                    formData.append('cabinet_id', this.cabinetSelected.id);
                    formData.append('date_event', this.dateSelected);
                    formData.append('time_event_from', moment(this.timeSelectedFrom).format("HH:ss"));
                    formData.append('time_event_to', moment(this.timeSelectedTo).format("HH:ss"));
                    if (this.addUser) {
                        formData.append('first_name', this.firstName);
                        formData.append('last_name', this.lastName);
                        formData.append('phone', this.phone);
                        formData.append('gender', this.gender);
                    }
                    formData.append('status', this.status);
                    this.addEvent(formData);
                }
            }
        }
    }
}

</script>

