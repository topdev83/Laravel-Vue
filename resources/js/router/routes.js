function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  {
    path: '/',
    name: 'welcome',
    component:
      window.config.domainStart === 'register'
        ? page('auth/register.vue')
        : (window.config.isTenant) ? page('auth/login.vue')
          : page('welcome.vue')
  },

  { path: '/token/:token', name: 'token', component: page('auth/token.vue'), props: true },
  { path: '/login/oauth/:provider/:domain', name: 'token', component: page('auth/oauth.vue'), props: true },
  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ] 
  },

  { path: '/users',
    component: page('users/index.vue'),
    children: [
      { path: '', component: page('users/list'), name: 'users.index' }
    ]
  },

  { path: '/workstation',
    component: page('workstations/index.vue'),
    children: [
      { path: '', component: page('workstations/StationTable'), name: 'workstations.index' }
    ]
  },


  { path: '*', component: page('errors/404.vue') }
]
