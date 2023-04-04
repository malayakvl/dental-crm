<template>
    <div class="sidebar">
        <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">
            <ul>
                <li>
                    <a class="brand" />
                </li>
                <li class="active">
                    <a>
                        <i class="fas fa-tachometer-alt text-white" />
                        <span class="s-caption">Панель управления</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i class="fas fa-bell text-blue-350" />
                        <span class="s-caption">Уведомления</span>
                        <em>9</em>
                    </a>
                </li>

                <li>
                    <div class="separator" />
                </li>

                <li v-for="menu in leftMenu">
                    <a>
                        <i :class=menu.menu.icon />
                        <span class="s-caption">{{menu.menu.title}}</span>
                    </a>
                </li>

            </ul>
            <span class="mb-5 px-5 py-3 hidden md:block text-center text-xs">
                <ul class="nav-footer">
                    <li>
                        <a href="">About</a>
                    </li>
                    <li>
                        <a href="">Cookies</a>
                    </li>
                    <li>
                        <a href="">Privacy</a>
                    </li>
                    <li>
                        <a href="">Terms</a>
                    </li>
                </ul>
                <span class="copyright">@ 2021 Liveproshop</span>
            </span>
        </div>
    </div>
</template>
<script>
import { SidebarMenu } from 'vue-sidebar-menu';
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css';
import { mapState, mapActions, mapGetters } from "vuex";
import * as menuActions from "../../store/modules/Menu/types/actions"
import * as menuGetters from "../../store/modules/Menu/types/getters";

const separator = {
    template: `<hr style="border-color: rgba(233, 235, 240, 1); margin: 20px 0px; background-color: #e9ebf0; opacity: 1;">`
}

export default {
    name: "LeftNav",
    components: {
        SidebarMenu
    },
    props: [
        "defaultClinic"
    ],
    computed: {
        ...mapState(["clinicId", "user", "noticeCnt"]),
        ...mapGetters("Menu", {
            menu: menuGetters.GET_MENU,
        }),
    },
    watch: {
        clinicId(value) {
            console.log("clinic ID", value);
            this.getMenu();
        },
        noticeCnt(value) {
            const noticeIndx = this.leftMenu.findIndex(menu => menu.id === 'notifications' );
            if (value > 0) {
                this.leftMenu[noticeIndx].badge.text = value;
                this.leftMenu[noticeIndx].badge.class = 'vsm--badge_default badge-notice'
            } else {
                this.leftMenu[noticeIndx].badge.text = '';
                this.leftMenu[noticeIndx].badge.class = ''

            }
        },
        menu(values) {
            this.leftMenu = [];
            // const leftMenu = [
            //     {
            //         header: false,
            //         title: '',
            //         hiddenOnCollapse: false
            //     },
            //     {
            //         id: "notifications",
            //         href: { path: '/notifications' },
            //         title: 'Уведомления',
            //         icon: 'fas fa-bell',
            //         badge: {
            //             text: this.noticeCnt > 0 ? this.noticeCnt : '',
            //             class: this.noticeCnt > 0 ? 'vsm--badge_default badge-notice' : ''
            //         }
            //     },
            //     {
            //         component: separator
            //     },
            // ];
            // values.forEach(menu => {
            //     const _menu = {
            //         title: menu.menu.title,
            //         icon: menu.menu.icon,
            //     }
            //     if (menu.menu.link) {
            //         _menu.href = { path: menu.menu.link };
            //
            //     }
            //     if(menu.childs.length > 0) {
            //         const childs = [];
            //         menu.childs.forEach(child => {
            //             childs.push({
            //                 href: { path: child.link },
            //                 title: child.title,
            //             })
            //         });
            //         _menu.child = childs;
            //     }
            //     leftMenu.push(_menu);
            // })
            this.leftMenu = values;
        }
    },
    mounted() {
        this.onResize()
        window.addEventListener('resize', this.onResize)
    },
    data() {
        return {
            collapsed: false,
            cntNotice: '',
            themes: [
                {
                    name: 'Default theme',
                    input: ''
                },
                {
                    name: 'White theme',
                    input: 'white-theme'
                }
            ],
            isOnMobile: false,
            selectedTheme: 'custom-theme',
            selectedWidth: '299px',
            leftMenu: [
                {
                    header: false,
                    title: '',
                    hiddenOnCollapse: false
                },
                {
                    id: "notifications",
                    href: { path: '/notifications' },
                    title: 'Уведомления',
                    icon: 'fas fa-bell',
                    badge: {
                        text: '',
                        class: ''
                    }
                },
                {
                    component: separator
                },
                {
                    href: { path: '/clinic/list' },
                    title: 'Клиники',
                    icon: 'fas fa-clinic-medical',
                    child: [
                        {
                            href: { path: '/clinic/create' },
                            title: 'Создать',
                        }
                    ]
                },
            ],
        }
    },
    methods: {
        ...mapActions('Menu', {
            getMenu: menuActions.GET_MENU,
        }),
        collapseMenu() {
            this.collapsed = !this.collapsed;
        },
        onCollapse(collapsed) {
            console.log('onToggleCollapse', collapsed)
        },
        onToggleCollapse (collapsed) {
            console.log('onToggleCollapse')
        },
        onItemClick (event, item) {
            console.log('onItemClick')
            // console.log(event)
            // console.log(item)
        },
        onResize () {
            if (window.innerWidth <= 767) {
                this.isOnMobile = true
                this.collapsed = true
            } else {
                this.isOnMobile = false
                this.collapsed = false
            }
        },
    }

}
</script>
