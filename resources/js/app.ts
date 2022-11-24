import './bootstrap'
import {createApp} from 'vue'
import {Quasar, Loading, Notify, Dialog, QDate} from 'quasar'
import quasarLang from 'quasar/lang/vi'
import 'quasar/src/css/index.sass'
import App from './App.vue'
import {store} from './store'
import router from './router'
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/fontawesome-v6/fontawesome-v6.css'

const app = createApp(App)
app.use(store)
app.use(router)
app.use(Quasar, {
    plugins: {
        Loading, Notify, Dialog, QDate
    }, // import Quasar plugins and add here
    lang: {
        quasarLang
    },
    config: {
        loading: {},
        notify: {},
        date:{}
    }
})
app.mount("#app")
