import { Injectable } from '@angular/core';
import { ApiService } from './api';

export interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  price_converted?: number;
  currency?: string;
  category: string;
  image_url: string;
  stock: number;
  rating?: number;
  reviews?: number;
  active?: number;
}

@Injectable({ providedIn: 'root' })
export class ProductService {
  constructor(private api: ApiService) {}

  getAll(filters?: { category?: string; search?: string; currency?: string }) {
    const params: Record<string, string> = {};
    if (filters?.category) params['category'] = filters.category;
    if (filters?.search)   params['search']   = filters.search;
    if (filters?.currency) params['currency'] = filters.currency;
    return this.api.get<any>('/api/products', params);
  }

  getById(id: number) {
    return this.api.get<any>(`/api/products/${id}`);
  }

  getCategories() {
    return this.api.get<any>('/api/products/categories');
  }

  create(data: Partial<Product>) {
    return this.api.post<any>('/api/products', data);
  }

  update(id: number, data: Partial<Product>) {
    return this.api.put<any>(`/api/products/${id}`, data);
  }

  delete(id: number) {
    return this.api.delete<any>(`/api/products/${id}`);
  }
}