const routes = [
  {
    name: 'Layout',
    path: '/',
    component: () => import('layouts/Basic.vue'),
    children: [
      {path: '/login', component: () => import('pages/Login.vue')},
      {path: '', component: () => import('pages/Index.vue')}
    ]
  }
];

// Load module routes
if (typeof window.modules === 'object') {
  for (const moduleName in window.modules) {
    const moduleConf = window.modules[moduleName];
    moduleConf.routes.forEach(route => {
      route.component = () => import(`src/modules/${moduleName}/${route.componentPath}`);
      if (typeof route.parent === 'string') {
        for (const parentRoute of routes) {
          if (parentRoute.name === route.parent) {
            parentRoute.children.push(route);
          }
        }
      } else {
        routes.push(route);
      }
    });
  }
}

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
