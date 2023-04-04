<template>
        <div ref="scheduler">
            <div class="dhx_cal_navline">
                <div class="dhx_cal_prev_button">&nbsp;</div>
                <div class="dhx_cal_next_button">&nbsp;</div>
                <div class="dhx_cal_today_button"></div>
                <div class="dhx_cal_date"></div>
    <!--            <div class="dhx_cal_tab" name="day_tab"></div>-->
    <!--            <div class="dhx_cal_tab" name="week_tab"></div>-->
    <!--            <div class="dhx_cal_tab dhx_cal_tab_standalone" name="timeline_tab">Timeline</div>-->
                <div class="dhx_cal_tab dhx_cal_tab_standalone unit_tab" name="single_unit_tab">День</div>
                <div class="dhx_cal_tab dhx_cal_tab_standalone week_unit_tab active" name="week_unit_tab">Тиждень</div>
                <div class="dhx_cal_tab" name="month_tab"></div>
                <div class="dhx_minical_icon" id="dhx_minical_icon" @click="showCalendar">&nbsp;</div>
            </div>
            <div class="dhx_cal_header"></div>
            <div class="dhx_cal_data"></div>
        </div>
</template>
<script>
import 'dhtmlx-scheduler';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_minical';
// import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_agenda_view';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_units';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_limit';
import 'dhtmlx-scheduler/codebase/locale/locale_ua';
import moment from "moment";
import {mapState, mapActions, mapGetters, mapMutations } from "vuex";
import * as cabinetGetters from "../../store/modules/Cabinet/types/getters";
import * as cabinetActions from "../../store/modules/Cabinet/types/actions"
import * as schedulerMutations from "../../store/modules/Scheduler/types/mutations";

var html = function(id) {
    return document.getElementById(id);
};

export default {
    name: 'scheduler',
    props: {
        events: {
            type: Array,
            default () {
                return {events: []}
            }
        }
    },
    data() {
        return {
        }
    },
    computed: {
        ...mapState(["clinicId", "user"]),
        ...mapGetters("Cabinet", {
            cabinets: cabinetGetters.GET_CABINETS,
        }),
    },
    watch: {
        cabinets(values) {
            this.initScheduler();
        },
    },
    mounted: function () {
        this.getCabinets();
    },
    methods: {
        ...mapActions("Cabinet", {
            getCabinets: cabinetActions.GET_CABINETS,
        }),
        ...mapMutations('Scheduler', {
            setCabinet: schedulerMutations.SET_CABINET,
            setDate: schedulerMutations.SET_DATE,
            setShowModal: schedulerMutations.SET_SHOW_MODAL,
        }),
        initScheduler() {
            const self = this;
            const sections = [];
            this.cabinets.forEach(cabinet => {
                sections.push({key: cabinet.id, label: cabinet.name});
            });

            scheduler.locale.labels.week_unit_tab = "Тиждень";
            scheduler.locale.labels.single_unit_tab = "День";
            scheduler.locale.labels.section_custom="Section";
            scheduler.config.limit_start = new Date();
            scheduler.config.dblclick_create = true;
            scheduler.config.details_on_create=true;
            scheduler.config.details_on_dblclick=false;
            scheduler.config.first_hour = 8;
            scheduler.config.last_hour = 19;

            scheduler.createUnitsView({
                name:"week_unit",
                property:"section_id",
                list:sections,
                days:3
            });
            scheduler.date.week_unit_start = scheduler.date.week_start;


            scheduler.createUnitsView({
                name:"single_unit",
                property:"section_id",
                list:sections
            });

            scheduler.showLightbox = function(id) {
                var ev = scheduler.getEvent(id);
                scheduler.startLightbox(id, html("my_form"));
                scheduler.endLightbox(false, html("my_form"));
                self.setShowModal(true);
            };

            scheduler.attachEvent("onEmptyClick", function (date, e){
                const evData = scheduler.getActionData(e);
                self.setCabinet(evData.section);
                self.setDate(date);
            });

            // chanfe DATE event
            scheduler.attachEvent("onViewChange", function (new_mode , new_date){
                console.log(`onViewChange ${new_mode}`, new_date);
            });
            scheduler.init(this.$refs.scheduler, new Date(moment().format('YYYY'), moment().format('MM')-1, moment().format('DD')), "week_unit");
            scheduler.parse(this.$props.events);
        },

        showCalendar() {
            if (scheduler.isCalendarVisible())
                scheduler.destroyCalendar();
            else
                scheduler.renderCalendar({
                    position:"dhx_minical_icon",
                    date:scheduler.getState().date,
                    navigation:true,
                    handler:function (date, calendar){
                        scheduler.setCurrentView(date);
                        scheduler.destroyCalendar()
                    }
                });
        }
    }
}
</script>

<style>
/*@import "~dhtmlx-scheduler/codebase/dhtmlxscheduler_material.css";*/
</style>
