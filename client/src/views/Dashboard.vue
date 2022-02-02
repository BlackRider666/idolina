<template>
    <v-row>
      <v-col cols="6">
        <transition-group name="fade" mode="in-out">
          <v-card
            flat
            v-if="!registerForm && registerRoleValue === null || registerRoleValue === 'investor'"
            key="investor"
          >
            <h2 class="dashboard__register__title">Інвестор</h2>
            <div class="dashboard__investor__box justify-center align-center">
              <img src="@/assets/investor.png" alt="Investor">
            </div>
            <v-row justify="center">
              <v-col v-if="!registerForm" cols="4">
                <v-btn
                    color="primary"
                    class="mt-2"
                    rounded
                    block
                    @click="registerRole('investor')"
                >{{$t('btn.register','Register')}}</v-btn>
              </v-col>
              <v-col v-else cols="9">
                <div class="d-flex dashboard__register__subtitle">
                  <span>Будь</span>
                  <span class="break"></span>
                  <span class="dashboard__register__subtitle__last_span">найкращим!</span>
                </div>
              </v-col>
            </v-row>

          </v-card>
          <v-card
              v-if="registerForm && registerRoleValue === 'startup'"
              flat
              class="pt-14"
              key="another"
          >
            <h3 class="dashboard__form_title">Форма реєстрації</h3>
            <v-form
                ref="form"
                lazy-validation
                align="center"
                @submit="register"
            >
              <v-radio-group
                  v-model="user.form_management"
                  row
              >
                <v-radio
                    label="Стартап"
                    color="#847D7D"
                    value="startup"
                ></v-radio>
                <v-radio
                    label="Патент"
                    color="#847D7D"
                    value="patent"
                ></v-radio>
              </v-radio-group>
              <v-text-field
                  v-model="user.name"
                  :label="$t('placeholder.name', 'Name')"
                  outlined
                  prepend-inner-icon="mdi-account-outline"
                  :rules="[rules.required]"
              />
              <v-row dense>
                <v-col cols="6">
                  <v-select
                    v-model="user.country_id"
                    :items="countries"
                    item-text="name"
                    item-value="id"
                    :label="$t('article.placeholder.country','Country')"
                    prepend-inner-icon="mdi-database-search"
                    outlined
                  ></v-select>
                </v-col>
                <v-col cols="6">
                  <v-select
                      v-model="user.city_id"
                      :items="cities"
                      item-text="name"
                      item-value="id"
                      :label="$t('article.placeholder.city','City')"
                      prepend-inner-icon="mdi-database-search"
                      outlined
                  ></v-select>
                </v-col>
                <v-col cols="12">
                  <v-autocomplete
                      v-model="user.direction"
                      :items="directions"
                      hide-no-data
                      item-text="name"
                      item-value="id"
                      :label="$t('works.placeholder.direction','Direction')"
                      :placeholder="$t('works.placeholder.direction','Direction')"
                      prepend-inner-icon="mdi-database-search"
                      :search-input="directionSearch"
                      @update:search-input="(value) => changeDirection(value)"
                      return-object
                      outlined
                  ></v-autocomplete>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                      v-model="user.phone"
                      :label="$t('placeholder.phone', 'Phone')"
                      outlined
                      prepend-inner-icon="mdi-phone"
                      :rules="[rules.required, rules.phone]"
                  />
                </v-col>
                <v-col cols="6">
                  <v-text-field
                      v-model="user.site"
                      :label="$t('placeholder.site', 'Site')"
                      outlined
                      prepend-inner-icon="mdi-web"
                      :rules="[rules.required, rules.site]"
                  />
                </v-col>
                <v-col cols="6">
                  <v-text-field
                      v-model="user.email"
                      outlined
                      prepend-inner-icon="mdi-email-outline"
                      :label="$t('placeholder.email', 'Email')"
                      :rules="[rules.required, rules.email]"
                      type="email"
                  />
                </v-col>
                <v-col cols="6">
                  <v-file-input
                      prepend-inner-icon="mdi-file"
                      prepend-icon=""
                      :label="$t('placeholder.file','File')"
                      outlined
                      v-model="user.file"
                  ></v-file-input>
                </v-col>
              </v-row>

              <v-text-field
                  v-model="user.password"
                  :rules="[rules.required, rules.min]"
                  outlined
                  prepend-inner-icon="mdi-lock"
                  color="primary"
                  :type="passwordType"
                  :label="$t('placeholder.password', 'Password')"
              >
                <template v-slot:append>
                  <v-icon color="secondary" v-if="passwordType === 'password'" @click="passwordType = 'text'">mdi-eye</v-icon>
                  <v-icon color="primary" v-if="passwordType === 'text'" @click="passwordType = 'password'">mdi-eye</v-icon>
                </template>
              </v-text-field>
              <v-text-field
                  v-model="user.password_confirmation"
                  :type="passwordType"
                  :label="$t('placeholder.password_confirmation', 'Confirm Password')"
                  color="primary"
                  outlined
                  prepend-inner-icon="mdi-lock"
                  :rules="[rules.required, rules.min, rules.confirmation]"
              />
              <v-textarea
                  v-model="user.desc"
                  :label="$t('placeholder.desc', 'About')"
                  color="primary"
                  outlined
              ></v-textarea>
              <v-row justify="center">
                <v-col cols="4">
                  <v-btn
                      color="primary"
                      class="mt-2"
                      rounded
                      block
                      type="submit"
                  >{{$t('btn.register','Register')}}</v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card>
        </transition-group>
      </v-col>
      <v-col cols="6">
        <transition-group name="fade" mode="in-out">
        <v-card
            flat
            v-if="!registerForm && registerRoleValue === null || registerRoleValue === 'startup'"
            key="startup"
        >
          <h2 class="dashboard__register__title">СтартаПатент</h2>
          <div class="dashboard__startup__box justify-center align-center">
            <img src="@/assets/startup.png" alt="Startup">
          </div>
          <v-row justify="center">
            <v-col v-if="!registerForm" cols="4">
              <v-btn
                  color="primary"
                  class="mt-2"
                  rounded
                  block
                  @click="registerRole('startup')"
              >{{$t('btn.register','Register')}}</v-btn>
            </v-col>
            <v-col v-else cols="9">
              <div class="d-flex dashboard__register__subtitle">
                <span>Будь</span>
                <span class="break"></span>
                <span class="dashboard__register__subtitle__last_span">успішним!</span>
              </div>
            </v-col>
          </v-row>
        </v-card>
        <v-card
            flat
            v-if="registerForm && registerRoleValue === 'investor'"
            key="another"
            class="pt-14"
        >
          <h3 class="dashboard__form_title">Форма реєстрації</h3>
          <v-form
              ref="form"
              lazy-validation
              align="center"
              @submit="register"
          >
            <v-radio-group
                v-model="user.form_management"
                row
            >
              <v-radio
                  label="Фізична особа"
                  color="#847D7D"
                  value="phys"
              ></v-radio>
              <v-radio
                  label="Юридична особа"
                  color="#847D7D"
                  value="legal"
              ></v-radio>
            </v-radio-group>
            <v-text-field
                v-model="user.name"
                :label="$t('placeholder.name', 'Name')"
                outlined
                prepend-inner-icon="mdi-account-outline"
                :rules="[rules.required]"
            />
            <v-row dense>
              <v-col cols="6">
                <v-select
                    v-model="user.country_id"
                    :items="countries"
                    item-text="name"
                    item-value="id"
                    :label="$t('article.placeholder.country','Country')"
                    prepend-inner-icon="mdi-database-search"
                    outlined
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-select
                    v-model="user.city_id"
                    :items="cities"
                    item-text="name"
                    item-value="id"
                    :label="$t('article.placeholder.city','City')"
                    prepend-inner-icon="mdi-database-search"
                    outlined
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-text-field
                    v-model="user.phone"
                    :label="$t('placeholder.phone', 'Phone')"
                    outlined
                    prepend-inner-icon="mdi-phone"
                    :rules="[rules.required, rules.phone]"
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                    v-model="user.site"
                    :label="$t('placeholder.site', 'Site')"
                    outlined
                    prepend-inner-icon="mdi-web"
                    :rules="[rules.required, rules.site]"
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                    v-model="user.email"
                    outlined
                    prepend-inner-icon="mdi-email-outline"
                    :label="$t('placeholder.email', 'Email')"
                    :rules="[rules.required, rules.email]"
                    type="email"
                />
              </v-col>
              <v-col cols="6">
                <v-file-input
                    prepend-inner-icon="mdi-file"
                    prepend-icon=""
                    :label="$t('placeholder.file','File')"
                    outlined
                    v-model="user.file"
                ></v-file-input>
              </v-col>
            </v-row>

            <v-text-field
                v-model="user.password"
                :rules="[rules.required, rules.min]"
                outlined
                prepend-inner-icon="mdi-lock"
                color="primary"
                :type="passwordType"
                :label="$t('placeholder.password', 'Password')"
            >
              <template v-slot:append>
                <v-icon color="secondary" v-if="passwordType === 'password'" @click="passwordType = 'text'">mdi-eye</v-icon>
                <v-icon color="primary" v-if="passwordType === 'text'" @click="passwordType = 'password'">mdi-eye</v-icon>
              </template>
            </v-text-field>
            <v-text-field
                v-model="user.password_confirmation"
                :type="passwordType"
                :label="$t('placeholder.password_confirmation', 'Confirm Password')"
                color="primary"
                outlined
                prepend-inner-icon="mdi-lock"
                :rules="[rules.required, rules.min, rules.confirmation]"
            />
            <v-textarea
                v-model="user.desc"
                :label="$t('placeholder.desc', 'About')"
                color="primary"
                outlined
            ></v-textarea>
            <v-row justify="center">
              <v-col cols="4">
                <v-btn
                    color="primary"
                    class="mt-2"
                    rounded
                    block
                    type="submit"
                >{{$t('btn.register','Register')}}</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card>
        </transition-group>
      </v-col>
      <v-col cols="12" class="pt-14">
        <p class="dashboard__p"> <strong>I-Dolina</strong> - це міжнародний креативний центр, що надає повний цикл послуг для успішного запуску та розвитку стартапів.</p>
        <p class="dashboard__p"> Даний Веб-сайт створений для пошуку, збереження, обміну, інформування, а та- кож спілкування між засновниками стартапів, власниками патентів та потенці - йними інвесторами, що зацікавленні в співробітництві.</p>
        <p class="dashboard__p"> Веб - сайт працює, як з резидентами так і не резидентами України. Основною метою даного ресурсу являється оптимізація та комфортне спілкування між його користувачами та допуск до відповідних ресурсів, що на ньому зберігаються.</p>
      </v-col>
      <v-col cols="8" offset="2">
          <img src="@/assets/business_meeting_1.png" alt="Business Meeting 1">
      </v-col>
    </v-row>
</template>

<script>
// import {mapState} from "vuex";

import {mapState} from "vuex";

export default {
  name: "Dashboard",
  data() {
    return {
      registerRoleValue:null,
      registerForm:false,
      user: {
        form_management:null,
        country_id:null,
        city_id:null,
        name:'',
        email:'',
        phone:'',
        site:'',
        password:'',
        password_confirmation:'',
        desc:'',
        file:null,
        direction:null,
      },
      passwordType: 'password',
      rules: {
        required: value => !!value || 'Required.',
        min: v => v.length >= 8 || 'Min 8 characters',
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'Invalid e-mail.'
        },
        confirmation: v => v === this.user.password || 'Password mismatch',
        phone: value => {
          const pattern = /^[\\+]?[(]?[0-9]{3}[)]?[-\s\\.]?[0-9]{3}[-\s\\.]?[0-9]{4,6}$/im
          return pattern.test(value) || 'Invalid phone'
        },
        site:value => {
          const pattern = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\\+.~#?&//=]*)/g
          return pattern.test(value) || 'Invalid site'
        },
      },
      directionSearch:'',
    };
  },
  computed: {
    ...mapState({
      countries: (state) => state.country.countries,
      cities: (state) => state.city.cities,
      directions: (state) => state.direction.directions,
    }),
  },
  mounted() {
    this.$store.dispatch('country/downloadCountries');
  },
  methods: {
    registerRole(role) {
      this.registerRoleValue = role;
      this.registerForm = true;
      if (role === 'startup') {
        this.$store.dispatch('direction/downloadDirections')
      }
    },
    register(e) {
      e.preventDefault();
      e.stopPropagation();
      if (!this.$refs.form.validate()) return;
      this.$loading();
      this.user.role = this.registerRoleValue;
      this.$store.dispatch('auth/register',this.user)
          .then(() => {
            this.$loadingClose();
            this.$store.dispatch('account/downloadAccount')
            this.$router.push({name:'dashboard'})
          })
          .catch(error => {
            this.$loadingClose();
            this.$notify('','error', error.response.data.message);
          })
    },
    changeDirection(item) {
      if (item) {
        let directions = this.directions.find((o) => o.name === item);
        if (!directions) {
          let org = this.directions.find((i) => i.id === 'new');
          if (org) {
            org.name = item;
          } else {
            this.directions.push({id:'new',name:item});
          }
        }
      }
    },
  },
  watch: {
    'user.country_id': {
      handler () {
        this.$store.dispatch('city/downloadCities');
      },
      deep: true,
    },
  },
}
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Marck+Script&display=swap');
.dashboard {
  &__investor {
    &__box {
      height: 500px;
      display: flex;
      background: linear-gradient(332.92deg, rgba(77, 224, 215, 0.22) 22.84%, rgba(10, 9, 10, 0.22) 76.92%);
      border: 1px solid #B8B4B4;
      box-sizing: border-box;
      border-radius: 30px;
    }
  }
  &__startup {
    &__box {
      display: flex;
      height: 500px;
      background: linear-gradient(207.86deg, rgba(223, 56, 156, 0.18) 34.02%, rgba(115, 37, 84, 0) 95.21%);
      border: 1px solid #B8B4B4;
      box-sizing: border-box;
      border-radius: 30px;
    }
  }
  &__register__title {
    font-size: 72px;
    color: #373A36;
    text-align: center;
  }
  &__register__subtitle {
    font-family: Marck Script;
    font-style: normal;
    font-weight: normal;
    font-size: 64px;
    line-height: 80px;
    text-align: center;
    color: #373A36;
    flex-wrap: wrap;
    justify-content: space-between;
  }
  &__register__subtitle__last_span{
    margin-left: auto;
    order: 2;
  }
  &__form_title {
    font-size: 24px;
    color: #373A36;
    text-align: center;
  }
  &__p {
    margin-left: 40px;
    font-family: Montserrat;
    font-style: normal;
    font-size: 24px;
  }
}
.break {
  flex-basis: 100%;
  height: 0;
}
.fade-enter-active,
.fade-leave-active {
  transition: all 400ms ease;
}

.fade-leave-from {
  opacity: 0;
}

.fade-leave-to {
  transform: translateY(-600px);
}
.fade-enter-from {
  transform: translateY(600px);
}
.fade-enter-to {
  opacity: 1;
}
</style>
