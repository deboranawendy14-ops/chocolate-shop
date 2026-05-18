import { Component, signal } from '@angular/core';
import { CommonModule, NgIf, NgFor } from '@angular/common';
import { RouterLink, Router } from '@angular/router';
import { CartService, CartItem } from '../../../../core/services/cart';
import { AuthService } from '../../../../core/services/auth';

@Component({
  selector: 'app-cart-page',
  standalone: true,
  imports: [CommonModule, NgIf, NgFor, RouterLink],
  templateUrl: './cart-page.html',
  styleUrl: './cart-page.scss'
})
export class CartPage {
  isLoading = signal(false);
  orderSuccess = signal(false);
  error = signal('');

  constructor(
    public cartService: CartService,
    public authService: AuthService,
    private router: Router
  ) {}

  increaseQty(item: CartItem): void {
    if (item.quantity < item.stock) {
      this.cartService.updateQuantity(item.product_id, item.quantity + 1);
    }
  }

  decreaseQty(item: CartItem): void {
    if (item.quantity > 1) {
      this.cartService.updateQuantity(item.product_id, item.quantity - 1);
    } else {
      this.cartService.removeItem(item.product_id);
    }
  }

  removeItem(productId: number): void {
    this.cartService.removeItem(productId);
  }

  checkout(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/auth/login']);
      return;
    }

    this.isLoading.set(true);
    this.error.set('');

    this.cartService.checkout('USD').subscribe({
      next: () => {
        this.cartService.clearCart();
        this.orderSuccess.set(true);
        this.isLoading.set(false);
        setTimeout(() => this.router.navigate(['/orders']), 2000);
      },
      error: (err: any) => {
        this.error.set(err.error?.error || 'Erro ao finalizar pedido.');
        this.isLoading.set(false);
      }
    });
  }
}