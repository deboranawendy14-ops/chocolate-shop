import { Injectable, signal } from '@angular/core';
import { ApiService } from './api';

export interface CartItem {
  id: number;
  product_id: number;
  name: string;
  price: number;
  image_url: string;
  quantity: number;
  stock: number;
}

@Injectable({ providedIn: 'root' })
export class CartService {
  cartItems = signal<CartItem[]>([]);
  cartCount = signal(0);

  constructor(private api: ApiService) {
    this.loadLocalCart();
  }

  // Carrinho local (sem autenticação)
  private loadLocalCart(): void {
    const saved = localStorage.getItem('cart');
    if (saved) {
      const items = JSON.parse(saved);
      this.cartItems.set(items);
      this.cartCount.set(items.reduce((sum: number, i: CartItem) => sum + i.quantity, 0));
    }
  }

  private saveLocalCart(): void {
    localStorage.setItem('cart', JSON.stringify(this.cartItems()));
    this.cartCount.set(this.cartItems().reduce((sum, i) => sum + i.quantity, 0));
  }

  addItem(product: any, quantity: number = 1): void {
    const items = [...this.cartItems()];
    const existing = items.find(i => i.product_id === product.id);

    if (existing) {
      existing.quantity += quantity;
    } else {
      items.push({
        id: Date.now(),
        product_id: product.id,
        name: product.name,
        price: product.price,
        image_url: product.image_url || product.image,
        quantity,
        stock: product.stock
      });
    }

    this.cartItems.set(items);
    this.saveLocalCart();
  }

  updateQuantity(productId: number, quantity: number): void {
    const items = this.cartItems().map(i =>
      i.product_id === productId ? { ...i, quantity } : i
    ).filter(i => i.quantity > 0);
    this.cartItems.set(items);
    this.saveLocalCart();
  }

  removeItem(productId: number): void {
    const items = this.cartItems().filter(i => i.product_id !== productId);
    this.cartItems.set(items);
    this.saveLocalCart();
  }

  clearCart(): void {
    this.cartItems.set([]);
    localStorage.removeItem('cart');
    this.cartCount.set(0);
  }

  getTotal(): number {
    return this.cartItems().reduce((sum, i) => sum + (i.price * i.quantity), 0);
  }

  // Checkout via API
  checkout(currency: string = 'USD') {
    return this.api.post<any>('/api/orders', { currency });
  }
}