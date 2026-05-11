import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './home.html',
  styleUrl: './home.scss'
})
export class Home {
  features = [
    { icon: 'gift', title: 'Qualidade Premium', desc: 'Chocolates seleccionados dos melhores chocolateiros do mundo.' },
    { icon: 'truck', title: 'Entrega Rápida', desc: 'Entregamos em todo o país em até 48 horas.' },
    { icon: 'star', title: 'Satisfação Garantida', desc: '100% de satisfação ou o seu dinheiro de volta.' },
  ];

  categories = [
    { name: 'Chocolates Belgas', desc: 'Chocolates artesanais da Bélgica', color: '#5C3020' },
    { name: 'Chocolates Suíços', desc: 'Luxuosos chocolates suíços', color: '#8C6E63' },
    { name: 'Trufas Premium', desc: 'Trufas artesanais de alta qualidade', color: '#7A4A35' },
    { name: 'Chocolate Amargo', desc: 'Chocolates amargos intensos', color: '#A0784A' },
  ];

  bestsellers = [
    { id: 1, name: 'Trufa de Chocolate Belga', desc: 'Trufas artesanais com chocolate belga 70% cacau', price: 45.90, rating: 4.8, reviews: 124, image: 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=400' },
    { id: 2, name: 'Chocolate Suíço ao Leite', desc: 'Chocolate ao leite suíço premium', price: 38.50, rating: 4.9, reviews: 89, image: 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=400' },
    { id: 3, name: 'Trufa de Avelã', desc: 'Deliciosas trufas recheadas com avelã', price: 52.00, rating: 4.7, reviews: 156, image: 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=400' },
    { id: 4, name: 'Chocolate Amargo 85%', desc: 'Chocolate amargo intenso 85% cacau', price: 41.90, rating: 4.6, reviews: 67, image: 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=400' },
  ];
}