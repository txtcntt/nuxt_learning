export default {
    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        title: 'frontend',
        htmlAttrs: {
            lang: 'en'
        },
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: '' },
            { name: 'format-detection', content: 'telephone=no' }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
            {
                rel: "stylesheet",
                href:
                    "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            },
            {
                rel: "stylesheet",
                href:
                    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
            }
        ],
        script: [
            {
                src: "https://code.jquery.com/jquery-3.3.1.slim.min.js",
                type: "text/javascript"
            },
            {
                src:
                    "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js",
                type: "text/javascript"
            },
            {
                src:
                    "https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js",
                type: "text/javascript"
            }
        ]
    },
    loading: '~/components/Loading.vue',
    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [
        '~/assets/css/main.css',
    ],


    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        './plugins/mixins/user.js'
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        '@nuxtjs/axios',
        '@nuxtjs/auth-next'
    ],

    axios: {
        baseURL: 'http://127.0.0.1:8000/api/',
    },

    auth: {
        redirect: {
            login: '/login',
            logout: '/login',
            callback: '/',
            home: '/users'
        },
        strategies: {
            local: {                
                token: {
                    property: 'meta.token',
                    global: true,
                    // required: true,
                    // type: 'Bearer'
                },
                user: {
                    property: 'data',
                    autoFetch: true
                },
                endpoints: {
                    login: {
                        url: "login",
                        method: "post",
                    },
                    user: {
                        url: "user",
                        method: "get",
                    },
                    logout: {
                        url: "logout",
                        method: "post"
                    }
                }
            }
        }
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
    }
}
