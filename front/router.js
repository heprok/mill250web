import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      component: () => import('@/views/dashboard/Index'),
      children: [
        // Dashboard
        {
          name: 'Информационная панель',
          path: '',
          component: () => import('@/views/dashboard/Dashboard'),
        },
        // Manual
        {
          name: 'Список пород',
          path: 'manual/species',
          component: () => import('@/views/dashboard/pages/manual/Species'),
        },        
        {
          name: 'Люди',
          path: 'manual/people',
          component: () => import('@/views/dashboard/pages/manual/People'),
        },        
        {
          name: 'Простои',
          path: 'manual/downtime',
          component: () => import('@/views/dashboard/pages/manual/Downtime'),
        },        
        {
          name: 'Ошибки',
          path: 'manual/error',
          component: () => import('@/views/dashboard/pages/manual/Error'),
        },         
        {
          name: 'Рабочее расписание',
          path: 'manual/workschedule',
          component: () => import('@/views/dashboard/pages/manual/WorkSchedule'),
        },         
        {
          name: 'Стандартные длины',
          path: 'manual/standartlength',
          component: () => import('@/views/dashboard/pages/manual/StandartLength'),
        },        
        {
          name: 'Действия оператора',
          path: 'manual/action',
          component: () => import('@/views/dashboard/pages/manual/Action'),
        },       
        //reports 
        {
          name: 'Простои ',
          path: 'report/downtimes',
          component: () => import('@/views/dashboard/pages/report/Downtime'),
        },        
        {
          name: 'Аварии и сообщения',
          path: 'report/alert',
          component: () => import('@/views/dashboard/pages/report/event/Alert'),
        },        
        {
          name: 'Действия оператора ',
          path: 'report/action',
          component: () => import('@/views/dashboard/pages/report/event/Action'),
        },           
        {
          name: 'Брёвна',
          path: 'report/timber',
          component: () => import('@/views/dashboard/pages/report/Timber'),
        },            
        {
          name: 'Пиломатериалы из постава',
          path: 'report/boardfrompostav',
          component: () => import('@/views/dashboard/pages/report/BoardPostav'),
        },            
        {
          name: 'Брёвна из постава',
          path: 'report/timberfrompostav',
          component: () => import('@/views/dashboard/pages/report/TimberPostav'),
        },         
        {
          name: 'Хронология пиламатериалов',
          path: 'report/timberregisrty',
          component: () => import('@/views/dashboard/pages/report/TimberRegistry'),
        }, 
        //settings         
        {
          name: 'Настройки',
          path: 'settings',
          component: () => import('@/views/dashboard/pages/Settings'),
        },          
      ],
    },
  ],
})
