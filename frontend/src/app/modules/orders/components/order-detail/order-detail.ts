import { Component, OnInit, signal } from '@angular/core';
import { CommonModule, NgFor, NgIf } from '@angular/common';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { ApiService } from '../../../../core/services/api';

interface OrderItem {
  id: number;
  name: string;
  image_url: string;
  quantity: number;
  unit_price: number;
}

interface Order {
  id: number;
  status: string;
  total_usd: number;
  currency: string;
  total_local: number;
  created_at: string;
  items: OrderItem[];
}

@Component({
  selector: 'app-order-detail',
  standalone: true,
  imports: [CommonModule, NgFor, NgIf, RouterLink],
  templateUrl: './order-detail.html',
  styleUrl: './order-detail.scss'
})
export class OrderDetail implements OnInit {
  order = signal<Order | null>(null);
  isLoading = signal(true);
  error = signal('');

  constructor(
    private api: ApiService,
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.api.get<any>(`/api/orders/${id}`).subscribe({
        next: (res) => {
          this.order.set(res.data);
          this.isLoading.set(false);
        },
        error: () => {
          this.error.set('Erro ao carregar pedido.');
          this.isLoading.set(false);
        }
      });
    }
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