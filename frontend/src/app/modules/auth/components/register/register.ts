import { Component, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../../core/services/auth';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, NgIf, RouterLink, FormsModule],
  templateUrl: './register.html',
  styleUrl: './register.scss'
})
export class Register {
  name = '';
  email = '';
  password = '';
  confirmPassword = '';
  showPassword = signal(false);
  showConfirm = signal(false);
  isLoading = signal(false);
  error = signal('');

  constructor(private authService: AuthService, private router: Router) {}

  togglePassword() { this.showPassword.set(!this.showPassword()); }
  toggleConfirm() { this.showConfirm.set(!this.showConfirm()); }

  onSubmit() {
    if (!this.name || !this.email || !this.password || !this.confirmPassword) {
      this.error.set('Preencha todos os campos.');
      return;
    }
    if (this.password !== this.confirmPassword) {
      this.error.set('As passwords não coincidem.');
      return;
    }
    if (this.password.length < 6) {
      this.error.set('A password deve ter pelo menos 6 caracteres.');
      return;
    }

    this.isLoading.set(true);
    this.error.set('');

    this.authService.register(this.name, this.email, this.password).subscribe({
      next: (res: any) => {
        this.authService.saveSession(res.data.token, res.data.user);
        this.router.navigate(['/']);
      },
      error: (err: any) => {
        this.error.set(err.error?.error || 'Erro ao criar conta.');
        this.isLoading.set(false);
      },
      complete: () => this.isLoading.set(false)
    });
  }
}