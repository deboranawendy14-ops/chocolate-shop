import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: '', loadComponent: () => import('./modules/catalog/components/home/home').then(m => m.Home) },
  { path: 'catalog', loadComponent: () => import('./modules/catalog/components/product-list/product-list').then(m => m.ProductList) },
  { path: 'auth/login', loadComponent: () => import('./modules/auth/components/login/login').then(m => m.Login) },
  { path: 'auth/register', loadComponent: () => import('./modules/auth/components/register/register').then(m => m.Register) },
];