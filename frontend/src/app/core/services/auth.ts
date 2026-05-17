import { Injectable, signal } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from './api';

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

interface AuthResponse {
  success: boolean;
  data: {
    token: string;
    user: User;
  };
  message: string;
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  currentUser = signal<User | null>(null);
  isLoggedIn  = signal(false);

  constructor(private api: ApiService, private router: Router) {
    this.loadFromStorage();
  }

  private loadFromStorage(): void {
    const token = localStorage.getItem('token');
    const user  = localStorage.getItem('user');
    if (token && user) {
      this.currentUser.set(JSON.parse(user));
      this.isLoggedIn.set(true);
    }
  }

  login(email: string, password: string) {
    return this.api.post<AuthResponse>('/api/auth/login', { email, password });
  }

  register(name: string, email: string, password: string) {
    return this.api.post<AuthResponse>('/api/auth/register', { name, email, password });
  }

  saveSession(token: string, user: User): void {
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
    this.currentUser.set(user);
    this.isLoggedIn.set(true);
  }

  logout(): void {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.currentUser.set(null);
    this.isLoggedIn.set(false);
    this.router.navigate(['/']);
  }

  isAdmin(): boolean {
    return this.currentUser()?.role === 'admin';
  }
}