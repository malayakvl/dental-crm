<template>
    <div ref="scheduler"></div>
</template>
<!--<script src="./services/dhtmlxscheduler.js?v=5.3.12" type="text/javascript" charset="utf-8"></script>-->
<!--<script src="./services/ext/dhtmlxscheduler_units.js?v=5.3.12" type="text/javascript" charset="utf-8"></script>-->
<!--<link rel="stylesheet" href="./services/_dhtmlxscheduler_material.css?v=5.3.12" type="text/css" charset="utf-8">-->
<script>
import 'dhtmlx-scheduler';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_minical';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_agenda_view';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_units';
import 'dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_timeline';
var html = function(id) {
    return document.getElementById(id);
};
// import "dhtmlx-scheduler/codebase/ext/dhtmlxscheduler_units";
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

    mounted: function () {
        // const schedulerLoader = document.createElement('script')
        // schedulerLoader.setAttribute('src', './services/dhtmlxscheduler.js');
        // document.head.appendChild(schedulerLoader);


        var sections=[
            {key:1, label:"Section A"},
            {key:2, label:"Section B"},
            {key:3, label:"Section C"},
            {key:4, label:"Section D"}
        ];


        scheduler.locale.labels.unit_tab = "Unit"
        scheduler.locale.labels.timelinet_tab = "Unit"
        scheduler.locale.labels.section_custom="Section";
        scheduler.config.details_on_create=true;
        scheduler.config.details_on_dblclick=true;


        const days = 2;
        scheduler.createTimelineView({
            name:	"timeline",
            x_unit:	"minute",
            x_date:	"%H:%i",
            x_step:	60,
            x_size: 24*days,
            x_start: 8,
            x_length: 24,
            y_unit:	sections,
            y_property:	"section_id",
            render:"bar",
            event_dy: "full",
            second_scale:{
                x_unit: "day", // unit which should be used for second scale
                x_date: "%F %d" // date format which should be used for second scale, "July 01"
            }
        });
        // scheduler.createTimelineView({
        //     name:	"timeline",
        //     x_unit:	"hour",
        //     x_date:	"%H:%i",
        //     x_step:	1,
        //     x_size: 24*days,
        //     scrollable:true,
        //     scroll_position: new Date(2017, 6, 2),
        //     column_width:70,
        //     x_length:	24*days,
        //     y_unit:	sections,
        //     y_property:	"section_id",
        //     render:"bar",
        //     second_scale:{
        //         x_unit: "day", // unit which should be used for second scale
        //         x_date: "%F %d" // date format which should be used for second scale, "July 01"
        //     }
        // });

        scheduler.showLightbox = function(id) {
            var ev = scheduler.getEvent(id);
            scheduler.startLightbox(id, html("my_form"));

            html("description").focus();
            html("description").value = ev.text;
            html("custom1").value = ev.custom1 || "";
            html("custom2").value = ev.custom2 || "";
        };
        // scheduler.config.lightbox.sections=[
        //     {name:"description", height:130, map_to:"text", type:"textarea" , focus:true},
        //     {name:"custom", height:23, type:"select", options:sections, map_to:"section_id" },
        //     {name:"time", height:72, type:"time", map_to:"auto"}
        // ]

        scheduler.createUnitsView({
            name:"unit",
            property:"section_id",
            list:sections,
            days:3
        });
        scheduler.config.multi_day = true;
        scheduler.config.first_hour = 8;
        scheduler.config.last_hour = 19;

        scheduler.init(this.$refs.scheduler, new Date(2021, 10, 20), "timeline");
        // scheduler.attachEvent("onDblClick", (id, ev) => {
        //     console.log("onDblClick");
        //     this.$emit("event-updated", id, "inserted", ev);
        // });
        scheduler.attachEvent("onDblClick", function (id, e){
            //any custom logic here
            return true;
        })
    },
    methods: {

    }
}
</script>

<style>
/*@import "~dhtmlx-scheduler/codebase/_dhtmlxscheduler_material.css";*/
#my_form {
    position: absolute;
    top: 100px;
    left: 200px;
    z-index: 10001;
    display: none;
    background-color: white;
    border: 2px outset gray;
    padding: 20px;
    font-family: Tahoma;
    font-size: 10pt;
}

#my_form label {
    width: 200px;
}
</style>
