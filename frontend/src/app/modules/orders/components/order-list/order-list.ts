import { Component, OnInit, signal } from '@angular/core';
import { CommonModule, NgFor, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../../../core/services/api';
import { AuthService } from '../../../../core/services/auth';

interface Order {
  id: number;
  status: string;
  total_usd: number;
  currency: string;
  total_local: number;
  created_at: string;
}

@Component({
  selector: 'app-order-list',
  standalone: true,
  imports: [CommonModule, NgFor, NgIf, RouterLink],
  templateUrl: './order-list.html',
  styleUrl: './order-list.scss'
})
export class OrderList implements OnInit {
  orders = signal<Order[]>([]);
  isLoading = signal(true);
  error = signal('');

  constructor(
    private api: ApiService,
    public authService: AuthService
  ) {}

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.error.set('Precisa de estar autenticado para ver os pedidos.');
      this.isLoading.set(false);
      return;
    }
    this.loadOrders();
  }

  loadOrders(): void {
    this.api.get<any>('/api/orders').subscribe({
      next: (res) => {
        this.orders.set(res.data);
        this.isLoading.set(false);
      },
      error: () => {
        this.error.set('Erro ao carregar pedidos.');
        this.isLoading.set(false);
      }
    });
  }

  getStatusLabel(status: string): string {
    const labels: Record<string, string> = {
      pending:    'Pendente',
      processing: 'Em processamento',
      shipped:    'Enviado',
      delivered:  'Entregue',
      cancelled:  'Cancelado'
    };
    return labels[status] ?? status;
  }

  getStatusClass(status: string): string {
    const classes: Record<string, string> = {
      pending:    'status--pending',
      processing: 'status--processing',
      shipped:    'status--shipped',
      delivered:  'status--delivered',
      cancelled:  'status--cancelled'
    };
    return classes[status] ?? '';
  }
}