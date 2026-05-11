import { Component, signal } from '@angular/core';
import { CommonModule, NgFor, NgIf } from '@angular/common';

interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  category: string;
  image: string;
  stock: number;
}

@Component({
  selector: 'app-product-list',
  standalone: true,
  imports: [CommonModule, NgFor, NgIf],
  templateUrl: './product-list.html',
  styleUrl: './product-list.scss',
})
export class ProductList {
  products: Product[] = [
    { id: 1, name: 'Chocolate Negro 70%', description: 'Intenso e aromático, feito com cacau seleccionado.', price: 5.99, category: 'Negro', image: 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=400', stock: 20 },
    { id: 2, name: 'Chocolate de Leite', description: 'Suave e cremoso, perfeito para todas as idades.', price: 4.99, category: 'Leite', image: 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=400', stock: 15 },
    { id: 3, name: 'Chocolate Branco', description: 'Doce e delicado com notas de baunilha.', price: 4.50, category: 'Branco', image: 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=400', stock: 4 },
    { id: 4, name: 'Trufas de Caramelo', description: 'Trufas artesanais com recheio de caramelo salgado.', price: 8.99, category: 'Trufas', image: 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=400', stock: 10 },
    { id: 5, name: 'Chocolate com Avelã', description: 'Crocante e irresistível com pedaços de avelã.', price: 6.50, category: 'Recheado', image: 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=400', stock: 8 },
    { id: 6, name: 'Bombons Sortidos', description: 'Caixa com 12 bombons de diferentes sabores.', price: 12.99, category: 'Bombons', image: 'https://images.unsplash.com/photo-1553452118-621e1f860f43?w=400', stock: 3 },
  ];

  t(key: string): string {
    const translations: Record<string, string> = {
      'catalog.title': 'Os Nossos Chocolates',
      'catalog.subtitle': 'Feitos com amor e os melhores ingredientes'
    };
    return translations[key] ?? key;
  }

  addToCart(product: Product): void {
    console.log('Adicionado ao carrinho:', product.name);
  }
}