import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';

import clearValidationErrors from './middleware/clearValidationErrors';
import clearError from './middleware/clearError';
import hasRole, { ROLE_DRIVER, ROLE_MECHANIC } from './middleware/hasRole';
import guest from './middleware/guest';
import auth from './middleware/auth';

import Home from '../views/home/Home.vue';
import Register from '../views/Register.vue';
import Login from '../views/auth/Login.vue';
import Profile from '../views/mechanic/Profile.vue';
import DriverProfile from '../views/driver/Profile.vue';
import CreateService from '../views/mechanic/CreateService.vue';
import CreateJob from '../views/driver/CreateJob.vue';
import ViewJob from '../views/driver/ViewJob.vue';
import ActiveJob from '../views/driver/ActiveJob.vue';

Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/login',
    name: 'auth.login',
    component: Login,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/mechanics/:id/profile',
    name: 'mechanic.profile',
    component: Profile,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/mechanics/services/create',
    name: 'mechanic.service.create',
    component: CreateService,
    meta: {
      requiresAuth: true,
      role: ROLE_MECHANIC,
    },
  },
  {
    path: '/drivers/jobs/create',
    name: 'driver.job.create',
    component: CreateJob,
    meta: {
      requiresAuth: true,
      role: ROLE_DRIVER,
    },
  },
  {
    path: '/drivers/jobs/:id',
    name: 'driver.job.show',
    component: ViewJob,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/drivers/:id/profile',
    name: 'driver.profile',
    component: DriverProfile,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/drivers/jobs/active/:id',
    name: 'driver.job.active.show',
    component: ActiveJob,
    meta: {
      requiresAuth: true,
    },
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
});

router.beforeEach(clearValidationErrors);
router.beforeEach(clearError);
router.beforeEach(hasRole);
router.beforeEach(guest);
router.beforeEach(auth);

export default router;
