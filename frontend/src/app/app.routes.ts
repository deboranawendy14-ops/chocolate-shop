import { Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: '',
    loadComponent: () => import('./modules/catalog/components/home/home').then(m => m.Home)
  },
  {
    path: 'catalog',
    loadComponent: () => import('./modules/catalog/components/product-list/product-list').then(m => m.ProductList)
  },
  {
    path: 'cart',
    loadComponent: () => import('./modules/cart/components/cart-page/cart-page').then(m => m.CartPage)
  },
  {
    path: 'orders',
    loadComponent: () => import('./modules/orders/components/order-list/order-list').then(m => m.OrderList)
  },
  {
    path: 'orders/:id',
    loadComponent: () => import('./modules/orders/components/order-detail/order-detail').then(m => m.OrderDetail)
  },
  {
    path: 'profile',
    loadComponent: () => import('./modules/profile/components/profile-view/profile-view').then(m => m.ProfileView)
  },
  {
    path: 'auth/login',
    loadComponent: () => import('./modules/auth/components/login/login').then(m => m.Login)
  },
  {
    path: 'auth/register',
    loadComponent: () => import('./modules/auth/components/register/register').then(m => m.Register)
  },
  {
    path: 'auth/forgot-password',
    loadComponent: () => import('./modules/auth/components/forgot-password/forgot-password').then(m => m.ForgotPassword)
  },
  {
    path: '**',
    redirectTo: ''
  }
];