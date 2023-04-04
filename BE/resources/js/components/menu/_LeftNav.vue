<template>
    <div class="float-start left-sidebar">
<!--        <div class="logo">-->
<!--            <img src="/images/logo.svg">-->
<!--            <span class="brand">Dental CRM</span>-->
<!--        </div>-->
<!--        <div class="toggle-btn float-end"><a href="javascript:;" @click="collapseMenu"><i class="fas fa-angle-double-left"></i></a></div>-->
<!--        <div class="clearfix"></div>-->
<!--        <form class="form-search">-->
<!--            <div class="input-group mb-3">-->
<!--                <input class="form-control w-75"  id="exampleDataList" placeholder="Type to search...">-->
<!--                <a href="javascript:;" class="btn float-end"><i class="fas fa-search"></i></a>-->
<!--            </div>-->
<!--        </form>-->
<!--        <ul class="left-nav-bar">-->
<!--            <li><router-link :to="{path: `/dashboard`}"><i class="fas fa-tachometer-alt"></i> Панель управления</router-link></li>-->
<!--            <li>-->
<!--                <router-link :to="{path: `/notifications`}" class="position-relative">-->
<!--                    <i class="fas fa-bell"></i>-->
<!--                    <span v-if="noticeCnt" class="position-absolute top-0 left-4 translate-middle badge rounded-pill bg-danger">-->
<!--                        {{noticeCnt}}-->
<!--                    </span>-->
<!--                    Уведомления-->
<!--                </router-link>-->
<!--            </li>-->
<!--        </ul>-->
        <div class="separator"></div>
        <sidebar-menu
            v-model:collapsed="collapsed"
            :menu="leftMenu"
            :theme="selectedTheme"
            :show-one-child="true"
            :width="selectedWidth"
            @toggle-collapse="onCollapse"
            @update:collapsed="onToggleCollapse"
            @collapse="onCollapse"
            @item-click="onItemClick"

        >
            <div slot="header">
                <div class="logo">
                    <img src="/images/logo.svg">
                    <span class="brand">Dental CRM</span>
                </div>
                <div class="clearfix"></div>
                <form class="form-search">
                    <div class="input-group mb-3">
                        <input class="form-control w-75"  id="exampleDataList" placeholder="Type to search...">
                        <a href="javascript:;" class="btn float-end"><i class="fas fa-search"></i></a>
                    </div>
                </form>
            </div>
        </sidebar-menu>
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
            const leftMenu = [
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
                        text: this.noticeCnt > 0 ? this.noticeCnt : '',
                        class: this.noticeCnt > 0 ? 'vsm--badge_default badge-notice' : ''
                    }
                },
                {
                    component: separator
                },
            ];
            values.forEach(menu => {
                const _menu = {
                    title: menu.menu.title,
                    icon: menu.menu.icon,
                }
                if (menu.menu.link) {
                    _menu.href = { path: menu.menu.link };

                }
                if(menu.childs.length > 0) {
                    const childs = [];
                    menu.childs.forEach(child => {
                        childs.push({
                            href: { path: child.link },
                            title: child.title,
                        })
                    });
                    _menu.child = childs;
                }
                leftMenu.push(_menu);
            })
            this.leftMenu = leftMenu;
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
