<template>
  <v-app-bar
      app
      flat
      fixed
      height="85px"
      color="white"
  >
    <v-container>
      <v-row>
        <v-col cols="1">
          <router-link
              class="text-decoration-none d-flex pt-2"
              :to="{name:'dashboard'}"
          ><v-app-bar-nav-icon><img src="@/assets/logo.png" alt="ID"/></v-app-bar-nav-icon>
          </router-link>
        </v-col>
        <v-col cols="10">
          <div
            class="header__menu__box"
          >
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Головна</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Про нас</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Стартапи</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Патенти</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Інвестори</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Контакти</router-link>
            <router-link
                :to="{name:'dashboard'}"
                class="text-decoration-none header__menu__box__item"
            >Правила</router-link>
            <v-spacer/>
            <v-btn
              fab
              color="primary"
              size="20px"
              class="header__menu__box__item__last"
            >
              <v-icon color="secondary" large>mdi-gesture-tap</v-icon>
            </v-btn>
          </div>
        </v-col>
        <v-col cols="1">
          <v-btn
              fab
              color="primary"
              size="20px"
          ><v-icon
              large
              color="secondary"
            >mdi-exit-to-app</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-app-bar>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "AppHeader",
  data() {
    return {
      search:null,
      searchType:null,
      types: [
        {
          key:'users',
          value:'Users'
        },
        {
          key:'articles',
          value:'Articles'
        }
      ],
      rules: {
        required: value => !!value || 'Required.',
      },
    };
  },
  computed: {
    accountOptions() {
      let items = [];
      if (this.$store.getters['account/getAccount'].roles.filter((item) => item.name === 'superadmin').length > 0
          ||
          this.$store.getters['account/getAccount'].roles.filter((item) => item.name === 'conference creator').length > 0
      ) {
         items = [
          {
            title: this.account.full_name || '',
            divider: true,
            subtitleClass: 'text-center font-weight-bold',
            image: this.account.avatar_url || null,
          },
          {
            titleTKey: "header.menu.premium",
            titleTDefault: "Premium",
            icon: "mdi-account-outline",
            to: {
              name:'Premium',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.account",
            titleTDefault: "Account",
            icon: "mdi-account-outline",
            to: {
              name:'account',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.conferences",
            titleTDefault: "Conferences",
            icon: "mdi-book-open-variant",
            to: {
              name:'Conferences',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.articles",
            titleTDefault: "Articles",
            icon: "mdi-book-open-variant",
            to: {
              name:'Articles',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.logout",
            titleTDefault: "Logout",
            icon: "mdi-logout",
            to: {
              name:'logout',
            },
          },
        ];
      } else {
        items = [
          {
            title: this.account.full_name || '',
            divider: true,
            subtitleClass: 'text-center font-weight-bold',
            image: this.account.avatar_url || null,
          },
          {
            titleTKey: "header.menu.premium",
            titleTDefault: "Premium",
            icon: "mdi-account-outline",
            to: {
              name:'Premium',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.account",
            titleTDefault: "Account",
            icon: "mdi-account-outline",
            to: {
              name:'account',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.articles",
            titleTDefault: "Articles",
            icon: "mdi-book-open-variant",
            to: {
              name:'Articles',
            },
            divider: true,
          },
          {
            titleTKey: "header.menu.logout",
            titleTDefault: "Logout",
            icon: "mdi-logout",
            to: {
              name:'logout',
            },
          },
        ]
      }
      return {
        items: items,
        titleClass: "v-avatar _max-h-46 _w-auto",
        titleIcon: "mdi-account-outline",
        showArrow: true,
        openOnHover: false,
        badgeContent: null,
        badgeValue: true,
        nudgeBottom: 45,
      }
    },
    isLoggedIn() {
      return this.$store.getters['auth/getAuthToken'].length > 0;
    },
    ...mapState({
      account: state => state.account.user,
    })
  },
  mounted() {
  },
  methods: {
    goToPage({pageName, action}) {
      if (pageName) this.$router.push({name: pageName});
      if (action) action();
    },
    mainSearch(e) {
      e.preventDefault();
      e.stopPropagation();
      if (!this.$refs.form.validate()) return;
      this.$router.push({name:'Main Search', params:{type:this.searchType.key}, query: {title:this.search}})
    }
  },
}
</script>

<style scoped lang="scss">
.header__menu {
  &__box {
    background: #FFF9F9;
    border: 1px solid #000000;
    box-sizing: border-box;
    border-radius: 50px;
    display: flex;
    flex-wrap: nowrap;
    &__item {
      font-family: Montserrat;
      font-style: normal;
      font-weight: bold;
      font-size: 20px;
      color: #000000;
      padding: 13px 18px;
      border-radius: 50px;
      &__last {
        border: 1px solid #000;
      }
    }
  }
}
</style>
