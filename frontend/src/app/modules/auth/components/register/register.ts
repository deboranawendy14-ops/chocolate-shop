import { Component, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';

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
    this.isLoading.set(true);
    this.error.set('');
    setTimeout(() => this.isLoading.set(false), 1500);
  }
}