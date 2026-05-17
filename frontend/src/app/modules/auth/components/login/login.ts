import { Component, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../../core/services/auth';
@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, NgIf, RouterLink, FormsModule],
  templateUrl: './login.html',
  styleUrl: './login.scss'
})
export class Login {
  email = '';
  password = '';
  showPassword = signal(false);
  isLoading = signal(false);
  error = signal('');

  constructor(private authService: AuthService, private router: Router) {}

  togglePassword() {
    this.showPassword.set(!this.showPassword());
  }

  onSubmit() {
    if (!this.email || !this.password) {
      this.error.set('Preencha todos os campos.');
      return;
    }

    this.isLoading.set(true);
    this.error.set('');

    this.authService.login(this.email, this.password).subscribe({
      next: (res: any) => {
        this.authService.saveSession(res.data.token, res.data.user);
        this.router.navigate(['/']);
      },
      error: (err: any) => {
        this.error.set(err.error?.error || 'Erro ao fazer login.');
        this.isLoading.set(false);
      },
      complete: () => this.isLoading.set(false)
    });
  }
}