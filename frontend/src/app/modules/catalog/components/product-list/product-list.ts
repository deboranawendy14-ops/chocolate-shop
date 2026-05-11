import { Component, signal } from '@angular/core';
import { CommonModule, NgFor, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';

interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  category: string;
  image: string;
  stock: number;
  rating: number;
  reviews: number;
}

@Component({
  selector: 'app-product-list',
  standalone: true,
  imports: [CommonModule, NgFor, NgIf, RouterLink, FormsModule],
  templateUrl: './product-list.html',
  styleUrl: './product-list.scss',
})
export class ProductList {
  search = '';
  selectedCategory = signal('Todos');

  categories = ['Todos', 'Negro', 'Leite', 'Branco', 'Trufas', 'Recheado', 'Bombons'];

  allProducts: Product[] = [
    { id: 1, name: 'Chocolate Negro 70%', description: 'Intenso e aromático, feito com cacau seleccionado.', price: 5.99, category: 'Negro', image: 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=400', stock: 20, rating: 4.8, reviews: 124 },
    { id: 2, name: 'Chocolate de Leite', description: 'Suave e cremoso, perfeito para todas as idades.', price: 4.99, category: 'Leite', image: 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=400', stock: 15, rating: 4.6, reviews: 98 },
    { id: 3, name: 'Chocolate Branco', description: 'Doce e delicado com notas de baunilha.', price: 4.50, category: 'Branco', image: 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=400', stock: 4, rating: 4.3, reviews: 67 },
    { id: 4, name: 'Trufas de Caramelo', description: 'Trufas artesanais com recheio de caramelo salgado.', price: 8.99, category: 'Trufas', image: 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=400', stock: 10, rating: 4.9, reviews: 203 },
    { id: 5, name: 'Chocolate com Avelã', description: 'Crocante e irresistível com pedaços de avelã.', price: 6.50, category: 'Recheado', image: 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=400', stock: 8, rating: 4.7, reviews: 156 },
    { id: 6, name: 'Bombons Sortidos', description: 'Caixa com 12 bombons de diferentes sabores.', price: 12.99, category: 'Bombons', image: 'https://images.unsplash.com/photo-1553452118-621e1f860f43?w=400', stock: 3, rating: 4.8, reviews: 89 },
    { id: 7, name: 'Chocolate Amargo 85%', description: 'Intenso e poderoso para os amantes do cacau puro.', price: 7.50, category: 'Negro', image: 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?w=400', stock: 12, rating: 4.5, reviews: 74 },
    { id: 8, name: 'Trufas de Avelã', description: 'Deliciosas trufas recheadas com avelã tostada.', price: 9.99, category: 'Trufas', image: 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?w=400', stock: 6, rating: 4.7, reviews: 112 },
  ];

  get filteredProducts(): Product[] {
    return this.allProducts.filter(p => {
      const matchCategory = this.selectedCategory() === 'Todos' || p.category === this.selectedCategory();
      const matchSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
      return matchCategory && matchSearch;
    });
  }

  setCategory(cat: string) {
    this.selectedCategory.set(cat);
  }

  addToCart(product: Product) {
    console.log('Adicionado:', product.name);
  }
}