import { Component, OnInit, signal } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule, NgIf } from '@angular/common';
import { AuthService } from '../../../core/services/auth';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, NgIf, RouterLink, RouterLinkActive],
  templateUrl: './navbar.html',
  styleUrl: './navbar.scss'
})
export class NavbarComponent implements OnInit {
  isDarkTheme = signal(false);
  currentLang = signal<'pt' | 'en'>('pt');
  isMenuOpen = signal(false);
  cartCount = signal(0);

  constructor(public authService: AuthService) {}

  translations: Record<string, Record<string, string>> = {
    pt: {
      home: 'Início',
      catalog: 'Produtos',
      categories: 'Categorias',
      orders: 'Pedidos',
      stock: 'Stock',
      login: 'Entrar',
      logout: 'Sair',
      register: 'Registar',
      profile: 'Perfil'
    },
    en: {
      home: 'Home',
      catalog: 'Products',
      categories: 'Categories',
      orders: 'Orders',
      stock: 'Stock',
      login: 'Login',
      logout: 'Sign Out',
      register: 'Register',
      profile: 'Profile'
    }
  };

  t(key: string): string {
    return this.translations[this.currentLang()][key] ?? key;
  }

  ngOnInit(): void {
    const savedTheme = localStorage.getItem('theme') as 'dark' | 'light' | null;
    const savedLang  = localStorage.getItem('lang') as 'pt' | 'en' | null;
    if (savedTheme === 'dark') {
      this.isDarkTheme.set(true);
      document.documentElement.setAttribute('data-theme', 'dark');
    }
    if (savedLang) this.currentLang.set(savedLang);
  }

  toggleTheme(): void {
    const next = !this.isDarkTheme();
    this.isDarkTheme.set(next);
    document.documentElement.setAttribute('data-theme', next ? 'dark' : 'light');
    localStorage.setItem('theme', next ? 'dark' : 'light');
  }

  toggleLang(): void {
    const next = this.currentLang() === 'pt' ? 'en' : 'pt';
    this.currentLang.set(next);
    localStorage.setItem('lang', next);
  }

  toggleMenu(): void {
    this.isMenuOpen.set(!this.isMenuOpen());
  }

  logout(): void {
    this.authService.logout();
  }
}