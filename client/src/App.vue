<template>
  <v-app>
    <v-dialog content-class="loading" transition="0" v-model="$store.state.showLoading" fullscreen>
      <Loading/>
    </v-dialog>
    <notification-snackbar/>
    <router-view></router-view>
  </v-app>
</template>

<script>
import Loading from "./components/loading/Loading";
import NotificationSnackbar from "./components/notification/Notification";
import {mapState} from "vuex";

export default {
  name: 'App',
  components: {NotificationSnackbar, Loading},
  data: function() {
    return {}
  },
  created() {
    this.$store.dispatch('l10s/getActiveLocales');
    if (this.$store.getters['auth/getAuthToken']) {
      this.$store.dispatch('account/downloadAccount');
    }
    this.updateTranslations();
  },
  computed: {
    ...mapState({
      locale: (state) => state.l10s.locale,
    }),
  },
  watch: {
    locale: function () {
      this.updateTranslations();
    },
  },
  methods: {
    updateTranslations(){
      this.$store.dispatch('l10s/getAllTranslations', this.$store.getters['l10s/getActiveLocale'].iso_code)
          .then(() => {
            this.l10s.onUntranslatedKeyFound((key, value) => {
              this.$store.dispatch('l10s/createNewTranslationKey', {
                key,
                value,
                iso_code: this.$store.getters['l10s/getActiveLocale'].iso_code,
              });
            })
          });
    }
  },
};
</script>
<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

$body-font-family: 'Playfair Display';
$title-font: 'Montserrat';
.v-application {
  font-family: $body-font-family, sans-serif !important;
  .title {
    font-family: $title-font, sans-serif !important;
  }
}
</style>
