USE chocolate_shop;

-- Admin e cliente de teste
INSERT INTO users (name, email, password_hash, role) VALUES
('Administrador', 'admin@chocolateshop.ao', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Cliente Teste', 'cliente@chocolateshop.ao', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

-- Produtos
INSERT INTO products (name, description, price, category, image_url) VALUES
('Chocolate Negro 70%', 'Intenso e aromático, feito com cacau seleccionado.', 5.99, 'Negro', 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=400'),
('Chocolate de Leite', 'Suave e cremoso, perfeito para todas as idades.', 4.99, 'Leite', 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=400'),
('Chocolate Branco', 'Doce e delicado com notas de baunilha.', 4.50, 'Branco', 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=400'),
('Trufas de Caramelo', 'Trufas artesanais com recheio de caramelo salgado.', 8.99, 'Trufas', 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=400'),
('Chocolate com Avelã', 'Crocante e irresistível com pedaços de avelã.', 6.50, 'Recheado', 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=400'),
('Bombons Sortidos', 'Caixa com 12 bombons de diferentes sabores.', 12.99, 'Bombons', 'https://images.unsplash.com/photo-1553452118-621e1f860f43?w=400'),
('Chocolate Amargo 85%', 'Intenso e poderoso para os amantes do cacau puro.', 7.50, 'Negro', 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?w=400'),
('Trufas de Avelã', 'Deliciosas trufas recheadas com avelã tostada.', 9.99, 'Trufas', 'https://images.unsplash.com/photo-1610450949065-1f2841536c88?w=400');

-- Stock
INSERT INTO stock (product_id, quantity, min_alert) VALUES
(1, 20, 5),
(2, 15, 5),
(3, 4,  5),
(4, 10, 5),
(5, 8,  5),
(6, 3,  5),
(7, 12, 5),
(8, 6,  5);