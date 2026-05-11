import { Component, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';

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
    setTimeout(() => this.isLoading.set(false), 1500);
  }
}