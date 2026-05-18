import { Component, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../../../core/services/api';

@Component({
  selector: 'app-forgot-password',
  standalone: true,
  imports: [CommonModule, NgIf, RouterLink, FormsModule],
  templateUrl: './forgot-password.html',
  styleUrl: './forgot-password.scss'
})
export class ForgotPassword {
  email = '';
  isLoading = signal(false);
  success = signal(false);
  error = signal('');

  constructor(private api: ApiService) {}

  onSubmit(): void {
    if (!this.email) {
      this.error.set('Introduza o seu email.');
      return;
    }
    this.isLoading.set(true);
    this.error.set('');
    this.api.post<any>('/api/auth/forgot-password', { email: this.email }).subscribe({
      next: () => {
        this.success.set(true);
        this.isLoading.set(false);
      },
      error: () => {
        this.error.set('Erro ao enviar pedido.');
        this.isLoading.set(false);
      }
    });
  }
}