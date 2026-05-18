import { Component, OnInit, signal } from '@angular/core';
import { CommonModule, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../../../core/services/auth';
import { CartService } from '../../../../core/services/cart';

@Component({
  selector: 'app-profile-view',
  standalone: true,
  imports: [CommonModule, NgIf, RouterLink],
  templateUrl: './profile-view.html',
  styleUrl: './profile-view.scss'
})
export class ProfileView implements OnInit {
  constructor(
    public authService: AuthService,
    public cartService: CartService
  ) {}

  ngOnInit(): void {}

  logout(): void {
    this.authService.logout();
  }
}