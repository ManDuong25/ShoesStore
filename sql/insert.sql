INSERT INTO
    `sizes`(`id`, `name`)
VALUES
    ('35', 'Size 35'),
    ('36', 'Size 36'),
    ('37', 'Size 37'),
    ('38', 'Size 38'),
    ('39', 'Size 39'),
    ('40', 'Size 40'),
    ('41', 'Size 41'),
    ('42', 'Size 42'),
    ('43', 'Size 43'),
    ('44', 'Size 44');

INSERT INTO
    `categories`(`id`, `name`)
VALUES
    ('1', 'Running Shoes'),
    ('2', 'Walking Shoes'),
    ('3', 'Tennis'),
    ('4', 'Trail Running Shoes'),
    ('5', 'Basketball Shoes');

INSERT INTO
    `products` (
        `id`,
        `name`,
        `category_id`,
        `price`,
        `description`,
        `image`,
        `gender`,
        `giaNhap`
    )
VALUES
    (
        '4',
        'Asics GT-2000 11',
        '1',
        '100000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.',
        '../img/Running Shoes/Asics GT-2000 11.avif',
        '0',
        '50000'
    ),
    (
        '5',
        'On Women’s Cloudrunner',
        '1',
        '100000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.',
        '../img/Running Shoes/On Women’s Cloudrunner.avif',
        '1',
        '50000'
    ),
    (
        '6',
        'Saucony Guide 16',
        '1',
        '150000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.',
        '../img/Running Shoes/Saucony Guide 16.avif',
        '0',
        '100000'
    ),
    (
        '7',
        'Hoka Bondi 8',
        '1',
        '150000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.',
        '../img/Running Shoes/Hoka Bondi 8.avif',
        '1',
        '100000'
    ),
    (
        '8',
        'Brooks Women Ariel 20 Running Shoes',
        '1',
        '150000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running. ',
        '../img/Running Shoes/Brooks Women Ariel 20 Running.avif',
        '1',
        '100000'
    ),
    (
        '9',
        'Saucony Peregrine 13 Hiking Shoe',
        '1',
        '150000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running.',
        '../img/Running Shoes/Saucony Peregrine 13 Hiking Shoe.avif',
        '0',
        '100000'
    ),
    (
        '10',
        'Asics Gel-Excite 9',
        '1',
        '150000',
        'Has thick cushioning to absorb shock when the foot hits the road. Protects the forefoot and heel. Suitable for marathons or long-distance running. ',
        '../img/Running Shoes/Asics Gel-Excite 9.avif',
        '0',
        '100000'
    ),
    (
        '11',
        'Adidas Ultraboost Light Running Shoe',
        '2',
        '200000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/Adidas Ultraboost Light Running Shoe.avif',
        '1',
        '150000'
    ),
    (
        '12',
        'Ryka Devotion Plus 3 Walking Shoe',
        '2',
        '200000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ',
        '../img/Walking Shoes/Ryka Devotion Plus 3 Walking Shoe.avif',
        '0',
        '150000'
    ),
    (
        '13',
        'Brooks Glycerin GTS 20',
        '2',
        '200000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/Brooks Glycerin GTS 20.avif',
        '0',
        '150000'
    ),
    (
        '14',
        'Asics Gel-venture 8',
        '2',
        '200000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ',
        '../img/Walking Shoes/Asics Gel-venture 8.avif',
        '1',
        '150000'
    ),
    (
        '15',
        'New Balance Men’s Fresh Foam 1080 V11',
        '2',
        '200000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/New Balance Men’s Fresh Foam 1080 V11.avif',
        '1',
        '150000'
    ),
    (
        '16',
        'Hoka Bondi 7 Shoes',
        '2',
        '250000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/Hoka Bondi 7 Shoes.avif',
        '1',
        '200000'
    ),
    (
        '17',
        'Vionic Tokyo Sneaker',
        '2',
        '250000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ',
        '../img/Walking Shoes/Vionic Tokyo Sneaker.avif',
        '0',
        '200000'
    ),
    (
        '18',
        'Altra Women’s Lone Peak 7 Trail Running Shoe',
        '2',
        '250000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/Altra Women’s Lone Peak 7 Trail Running Shoe.avif',
        '1',
        '200000'
    ),
    (
        '19',
        'Keen Targhee Vent Hiking Shoes',
        '2',
        '250000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe. ',
        '../img/Walking Shoes/Keen Targhee Vent Hiking Shoes.avif',
        '0',
        '200000'
    ),
    (
        '20',
        'Allbirds Tree Runners',
        '2',
        '250000',
        'Lightweight, hugs the feet, reduces pain and muscle tension when walking. The slightly rounded sole helps transfer weight from heel to toe.',
        '../img/Walking Shoes/Allbirds Tree Runners.avif',
        '0',
        '200000'
    ),
    (
        '21',
        'NikeCourt Air Zoom Vapor Pro 2 (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/NikeCourt Air Zoom Vapor Pro 2 (Nam).avif',
        '0',
        '200000'
    ),
    (
        '22',
        'NikeCourt Air Zoom Vapor Pro 2 (Nữ)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/NikeCourt Air Zoom Vapor Pro 2.avif',
        '1',
        '250000'
    ),
    (
        '23',
        'Nike Zoom GP Challenge 1 (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Nike Zoom GP Challenge 1 (Nam).avif',
        '0',
        '250000'
    ),
    (
        '24',
        'Nike Zoom GP Challenge 1 (Nữ)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Nike Zoom GP Challenge 1.avif',
        '1',
        '250000'
    ),
    (
        '25',
        'Adidas Barricade Tokyo (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Adidas Barricade Tokyo (Nam).avif',
        '0',
        '250000'
    ),
    (
        '26',
        'Adidas Adizero Ubersonic 4 (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Adidas Adizero Ubersonic 4 (Nam).avif',
        '0',
        '250000'
    ),
    (
        '27',
        'Nike Vapor Lite HC (Nữ)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Nike Vapor Lite HC.avif',
        '1',
        '250000'
    ),
    (
        '28',
        'Adidas Gamecourt (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Adidas Gamecourt (Nam).avif',
        '0',
        '250000'
    ),
    (
        '29',
        'Adidas Solematch Bounce (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/Adidas Solematch Bounce (Nam).avif',
        '0',
        '250000'
    ),
    (
        '30',
        'Nike Air Zoom Vapor Pro (Nam)',
        '3',
        '300000',
        'Supports the inside and outside of the foot. .Flexible at the base for quick forward movement.',
        '../img/Tennis Shoes/NikeCourt Air Zoom Vapor Pro 2 (Nam).avif',
        '0',
        '250000'
    ),
    (
        '31',
        'Nike Pegasus Trail 4 GTX',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Nike Pegasus Trail 4 GTX.avif',
        '0',
        '300000'
    ),
    (
        '32',
        'Topo Athletic Ultraventure 3',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Topo Athletic Ultraventure 3.avif',
        '0',
        '300000'
    ),
    (
        '33',
        'Hoka Mafate Speed 4',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Hoka Mafate Speed 4.avif',
        '0',
        '300000'
    ),
    (
        '34',
        'Altra Lone Peak 7',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Altra Lone Peak 7.avif',
        '1',
        '300000'
    ),
    (
        '35',
        'Salomon Speedcross 5',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Salomon Speedcross 5.avif',
        '0',
        '300000'
    ),
    (
        '36',
        'Brooks Cascadia 16',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Brooks Cascadia 16.avif',
        '1',
        '300000'
    ),
    (
        '37',
        'Saucony Peregrine 11',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Saucony Peregrine 11.avif',
        '0',
        '300000'
    ),
    (
        '38',
        'La Sportiva Bushido II',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/La Sportiva Bushido II.avif',
        '0',
        '300000'
    ),
    (
        '39',
        'Inov-8 Terraultra G 270',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Inov-8 Terraultra G 270.avif',
        '0',
        '300000'
    ),
    (
        '40',
        'Merrell MTL Long Sky',
        '4',
        '350000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.Resistant to mud, soil, water and rocks. Bigger spikes for grip on uneven surfaces.',
        '../img/Trail Running Shoes/Merrell MTL Long Sky.avif',
        '1',
        '300000'
    ),
    (
        '41',
        'Nike Kyrie 7',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Nike Kyrie 7.avif',
        '1',
        '310000'
    ),
    (
        '42',
        'PEAK Streetball Master',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/PEAK Streetball Master.avif',
        '0',
        '310000'
    ),
    (
        '43',
        'Adidas Harden Stepback',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Adidas Harden Stepback.avif',
        '0',
        '310000'
    ),
    (
        '44',
        'Nike PG 5',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Nike PG 5.avif',
        '0',
        '310000'
    ),
    (
        '45',
        'Nike LeBron 19',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Nike LeBron 19.avif',
        '0',
        '310000'
    ),
    (
        '46',
        'Adidas Dame 7',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Adidas Dame 7.avif',
        '1',
        '310000'
    ),
    (
        '47',
        'Adidas N3XT L3V3L 2022',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Adidas N3XT L3V3L 2022.avif',
        '0',
        '310000'
    ),
    (
        '48',
        'Under Armour HOVR Havoc 5',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Under Armour HOVR Havoc 5.avif',
        '1',
        '310000'
    ),
    (
        '49',
        'Puma Clyde All-Pro',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/Puma Clyde All-Pro.avif',
        '1',
        '310000'
    ),
    (
        '50',
        'New Balance Kawhi Leonard 1',
        '5',
        '360000',
        'The sole is thick and hard, providing support when running up and down the court. The shoe collar is high, covering the ankle.',
        '../img/Basketball Shoes/New Balance Kawhi Leonard 1.avif',
        '0',
        '310000'
    );

INSERT INTO
    size_items (product_id, size_id, quantity)
VALUES
    (4, 35, 10),
    (4, 36, 10),
    (4, 37, 10),
    (4, 38, 10),
    (4, 39, 10),
    (4, 40, 10),
    (4, 41, 10),
    (4, 42, 10),
    (4, 43, 10),
    (4, 44, 10),
    (5, 35, 10),
    (5, 36, 10),
    (5, 37, 10),
    (5, 38, 10),
    (5, 39, 10),
    (5, 40, 10),
    (5, 41, 10),
    (5, 42, 10),
    (5, 43, 10),
    (5, 44, 10),
    (6, 35, 10),
    (6, 36, 10),
    (6, 37, 10),
    (6, 38, 10),
    (6, 39, 10),
    (6, 40, 10),
    (6, 41, 10),
    (6, 42, 10),
    (6, 43, 10),
    (6, 44, 10),
    (7, 35, 10),
    (7, 36, 10),
    (7, 37, 10),
    (7, 38, 10),
    (7, 39, 10),
    (7, 40, 10),
    (7, 41, 10),
    (7, 42, 10),
    (7, 43, 10),
    (7, 44, 10),
    (8, 35, 10),
    (8, 36, 10),
    (8, 37, 10),
    (8, 38, 10),
    (8, 39, 10),
    (8, 40, 10),
    (8, 41, 10),
    (8, 42, 10),
    (8, 43, 10),
    (8, 44, 10),
    (9, 35, 10),
    (9, 36, 10),
    (9, 37, 10),
    (9, 38, 10),
    (9, 39, 10),
    (9, 40, 10),
    (9, 41, 10),
    (9, 42, 10),
    (9, 43, 10),
    (9, 44, 10),
    (10, 35, 10),
    (10, 36, 10),
    (10, 37, 10),
    (10, 38, 10),
    (10, 39, 10),
    (10, 40, 10),
    (10, 41, 10),
    (10, 42, 10),
    (10, 43, 10),
    (10, 44, 10),
    (11, 35, 10),
    (11, 36, 10),
    (11, 37, 10),
    (11, 38, 10),
    (11, 39, 10),
    (11, 40, 10),
    (11, 41, 10),
    (11, 42, 10),
    (11, 43, 10),
    (11, 44, 10),
    (12, 35, 10),
    (12, 36, 10),
    (12, 37, 10),
    (12, 38, 10),
    (12, 39, 10),
    (12, 40, 10),
    (12, 41, 10),
    (12, 42, 10),
    (12, 43, 10),
    (12, 44, 10),
    (13, 35, 10),
    (13, 36, 10),
    (13, 37, 10),
    (13, 38, 10),
    (13, 39, 10),
    (13, 40, 10),
    (13, 41, 10),
    (13, 42, 10),
    (13, 43, 10),
    (13, 44, 10),
    (14, 35, 10),
    (14, 36, 10),
    (14, 37, 10),
    (14, 38, 10),
    (14, 39, 10),
    (14, 40, 10),
    (14, 41, 10),
    (14, 42, 10),
    (14, 43, 10),
    (14, 44, 10),
    (15, 35, 10),
    (15, 36, 10),
    (15, 37, 10),
    (15, 38, 10),
    (15, 39, 10),
    (15, 40, 10),
    (15, 41, 10),
    (15, 42, 10),
    (15, 43, 10),
    (15, 44, 10),
    (16, 35, 10),
    (16, 36, 10),
    (16, 37, 10),
    (16, 38, 10),
    (16, 39, 10),
    (16, 40, 10),
    (16, 41, 10),
    (16, 42, 10),
    (16, 43, 10),
    (16, 44, 10),
    (17, 35, 10),
    (17, 36, 10),
    (17, 37, 10),
    (17, 38, 10),
    (17, 39, 10),
    (17, 40, 10),
    (17, 41, 10),
    (17, 42, 10),
    (17, 43, 10),
    (17, 44, 10),
    (18, 35, 10),
    (18, 36, 10),
    (18, 37, 10),
    (18, 38, 10),
    (18, 39, 10),
    (18, 40, 10),
    (18, 41, 10),
    (18, 42, 10),
    (18, 43, 10),
    (18, 44, 10),
    (19, 35, 10),
    (19, 36, 10),
    (19, 37, 10),
    (19, 38, 10),
    (19, 39, 10),
    (19, 40, 10),
    (19, 41, 10),
    (19, 42, 10),
    (19, 43, 10),
    (19, 44, 10),
    (20, 35, 10),
    (20, 36, 10),
    (20, 37, 10),
    (20, 38, 10),
    (20, 39, 10),
    (20, 40, 10),
    (20, 41, 10),
    (20, 42, 10),
    (20, 43, 10),
    (20, 44, 10),
    (21, 35, 10),
    (21, 36, 10),
    (21, 37, 10),
    (21, 38, 10),
    (21, 39, 10),
    (21, 40, 10),
    (21, 41, 10),
    (21, 42, 10),
    (21, 43, 10),
    (21, 44, 10),
    (22, 35, 10),
    (22, 36, 10),
    (22, 37, 10),
    (22, 38, 10),
    (22, 39, 10),
    (22, 40, 10),
    (22, 41, 10),
    (22, 42, 10),
    (22, 43, 10),
    (22, 44, 10),
    (23, 35, 10),
    (23, 36, 10),
    (23, 37, 10),
    (23, 38, 10),
    (23, 39, 10),
    (23, 40, 10),
    (23, 41, 10),
    (23, 42, 10),
    (23, 43, 10),
    (23, 44, 10),
    (24, 35, 10),
    (24, 36, 10),
    (24, 37, 10),
    (24, 38, 10),
    (24, 39, 10),
    (24, 40, 10),
    (24, 41, 10),
    (24, 42, 10),
    (24, 43, 10),
    (24, 44, 10),
    (25, 35, 10),
    (25, 36, 10),
    (25, 37, 10),
    (25, 38, 10),
    (25, 39, 10),
    (25, 40, 10),
    (25, 41, 10),
    (25, 42, 10),
    (25, 43, 10),
    (25, 44, 10),
    (26, 35, 10),
    (26, 36, 10),
    (26, 37, 10),
    (26, 38, 10),
    (26, 39, 10),
    (26, 40, 10),
    (26, 41, 10),
    (26, 42, 10),
    (26, 43, 10),
    (26, 44, 10),
    (27, 35, 10),
    (27, 36, 10),
    (27, 37, 10),
    (27, 38, 10),
    (27, 39, 10),
    (27, 40, 10),
    (27, 41, 10),
    (27, 42, 10),
    (27, 43, 10),
    (27, 44, 10),
    (28, 35, 10),
    (28, 36, 10),
    (28, 37, 10),
    (28, 38, 10),
    (28, 39, 10),
    (28, 40, 10),
    (28, 41, 10),
    (28, 42, 10),
    (28, 43, 10),
    (28, 44, 10),
    (29, 35, 10),
    (29, 36, 10),
    (29, 37, 10),
    (29, 38, 10),
    (29, 39, 10),
    (29, 40, 10),
    (29, 41, 10),
    (29, 42, 10),
    (29, 43, 10),
    (29, 44, 10),
    (30, 35, 10),
    (30, 36, 10),
    (30, 37, 10),
    (30, 38, 10),
    (30, 39, 10),
    (30, 40, 10),
    (30, 41, 10),
    (30, 42, 10),
    (30, 43, 10),
    (30, 44, 10),
    (31, 35, 10),
    (31, 36, 10),
    (31, 37, 10),
    (31, 38, 10),
    (31, 39, 10),
    (31, 40, 10),
    (31, 41, 10),
    (31, 42, 10),
    (31, 43, 10),
    (31, 44, 10),
    (32, 35, 10),
    (32, 36, 10),
    (32, 37, 10),
    (32, 38, 10),
    (32, 39, 10),
    (32, 40, 10),
    (32, 41, 10),
    (32, 42, 10),
    (32, 43, 10),
    (32, 44, 10),
    (33, 35, 10),
    (33, 36, 10),
    (33, 37, 10),
    (33, 38, 10),
    (33, 39, 10),
    (33, 40, 10),
    (33, 41, 10),
    (33, 42, 10),
    (33, 43, 10),
    (33, 44, 10),
    (34, 35, 10),
    (34, 36, 10),
    (34, 37, 10),
    (34, 38, 10),
    (34, 39, 10),
    (34, 40, 10),
    (34, 41, 10),
    (34, 42, 10),
    (34, 43, 10),
    (34, 44, 10),
    (35, 35, 10),
    (35, 36, 10),
    (35, 37, 10),
    (35, 38, 10),
    (35, 39, 10),
    (35, 40, 10),
    (35, 41, 10),
    (35, 42, 10),
    (35, 43, 10),
    (35, 44, 10),
    (36, 35, 10),
    (36, 36, 10),
    (36, 37, 10),
    (36, 38, 10),
    (36, 39, 10),
    (36, 40, 10),
    (36, 41, 10),
    (36, 42, 10),
    (36, 43, 10),
    (36, 44, 10),
    (37, 35, 10),
    (37, 36, 10),
    (37, 37, 10),
    (37, 38, 10),
    (37, 39, 10),
    (37, 40, 10),
    (37, 41, 10),
    (37, 42, 10),
    (37, 43, 10),
    (37, 44, 10),
    (38, 35, 10),
    (38, 36, 10),
    (38, 37, 10),
    (38, 38, 10),
    (38, 39, 10),
    (38, 40, 10),
    (38, 41, 10),
    (38, 42, 10),
    (38, 43, 10),
    (38, 44, 10),
    (39, 35, 10),
    (39, 36, 10),
    (39, 37, 10),
    (39, 38, 10),
    (39, 39, 10),
    (39, 40, 10),
    (39, 41, 10),
    (39, 42, 10),
    (39, 43, 10),
    (39, 44, 10),
    (40, 35, 10),
    (40, 36, 10),
    (40, 37, 10),
    (40, 38, 10),
    (40, 39, 10),
    (40, 40, 10),
    (40, 41, 10),
    (40, 42, 10),
    (40, 43, 10),
    (40, 44, 10),
    (41, 35, 10),
    (41, 36, 10),
    (41, 37, 10),
    (41, 38, 10),
    (41, 39, 10),
    (41, 40, 10),
    (41, 41, 10),
    (41, 42, 10),
    (41, 43, 10),
    (41, 44, 10),
    (42, 35, 10),
    (42, 36, 10),
    (42, 37, 10),
    (42, 38, 10),
    (42, 39, 10),
    (42, 40, 10),
    (42, 41, 10),
    (42, 42, 10),
    (42, 43, 10),
    (42, 44, 10),
    (43, 35, 10),
    (43, 36, 10),
    (43, 37, 10),
    (43, 38, 10),
    (43, 39, 10),
    (43, 40, 10),
    (43, 41, 10),
    (43, 42, 10),
    (43, 43, 10),
    (43, 44, 10),
    (44, 35, 10),
    (44, 36, 10),
    (44, 37, 10),
    (44, 38, 10),
    (44, 39, 10),
    (44, 40, 10),
    (44, 41, 10),
    (44, 42, 10),
    (44, 43, 10),
    (44, 44, 10),
    (45, 35, 10),
    (45, 36, 10),
    (45, 37, 10),
    (45, 38, 10),
    (45, 39, 10),
    (45, 40, 10),
    (45, 41, 10),
    (45, 42, 10),
    (45, 43, 10),
    (45, 44, 10),
    (46, 35, 10),
    (46, 36, 10),
    (46, 37, 10),
    (46, 38, 10),
    (46, 39, 10),
    (46, 40, 10),
    (46, 41, 10),
    (46, 42, 10),
    (46, 43, 10),
    (46, 44, 10),
    (47, 35, 10),
    (47, 36, 10),
    (47, 37, 10),
    (47, 38, 10),
    (47, 39, 10),
    (47, 40, 10),
    (47, 41, 10),
    (47, 42, 10),
    (47, 43, 10),
    (47, 44, 10),
    (48, 35, 10),
    (48, 36, 10),
    (48, 37, 10),
    (48, 38, 10),
    (48, 39, 10),
    (48, 40, 10),
    (48, 41, 10),
    (48, 42, 10),
    (48, 43, 10),
    (48, 44, 10),
    (49, 35, 10),
    (49, 36, 10),
    (49, 37, 10),
    (49, 38, 10),
    (49, 39, 10),
    (49, 40, 10),
    (49, 41, 10),
    (49, 42, 10),
    (49, 43, 10),
    (49, 44, 10),
    (50, 35, 10),
    (50, 36, 10),
    (50, 37, 10),
    (50, 38, 10),
    (50, 39, 10),
    (50, 40, 10),
    (50, 41, 10),
    (50, 42, 10),
    (50, 43, 10),
    (50, 44, 10);

INSERT INTO chucnang (maChucNang, tenChucNang)
VALUES 
    ('CN1', 'Xem'),
    ('CN2', 'Sửa'),
    ('CN3', 'Xoá'),
    ('CN4', 'Tạo');

INSERT INTO quyen (maQuyen, tenQuyen)
VALUES 
    ('Q1', 'Thống kê doanh thu'),
    ('Q2', 'Quản lí đơn hàng'),
    ('Q3', 'Quản lí sản phẩm'),
    ('Q4', 'Quản lí danh mục'),
    ('Q5', 'Quản lí kích cỡ'),
    ('Q6', 'Quản lí mã giảm giá'),
    ('Q7', 'Quản lí kho hàng'),
	 ('Q8', 'Quản lí tài khoản'),
	 ('Q9', 'Quản lí phân quyền'),
	 ('Q10', 'Quản lí phiếu nhập');
	 
INSERT INTO nhomquyen (maNhomQuyen, tenNhomQuyen, trangThai)
VALUES 
   ('NQ1', 'Admin', 1),
	('NQ2', 'Manager', 1),
	('NQ3', 'Employee', 1),
	('NQ4', 'Customer', 1);
	
INSERT INTO chitietquyen(maNhomQuyen, maChucNang, maQuyen)
VALUES
    -- Admin có full quyền và chức năng
    ('NQ1', 'CN1', 'Q1'),
    ('NQ1', 'CN2', 'Q1'),
    ('NQ1', 'CN3', 'Q1'),
    ('NQ1', 'CN4', 'Q1'),
    ('NQ1', 'CN1', 'Q2'),
    ('NQ1', 'CN2', 'Q2'),
    ('NQ1', 'CN3', 'Q2'),
    ('NQ1', 'CN4', 'Q2'),
    ('NQ1', 'CN1', 'Q3'),
    ('NQ1', 'CN2', 'Q3'),
    ('NQ1', 'CN3', 'Q3'),
    ('NQ1', 'CN4', 'Q3'),
    ('NQ1', 'CN1', 'Q4'),
    ('NQ1', 'CN2', 'Q4'),
    ('NQ1', 'CN3', 'Q4'),
    ('NQ1', 'CN4', 'Q4'),
    ('NQ1', 'CN1', 'Q5'),
    ('NQ1', 'CN2', 'Q5'),
    ('NQ1', 'CN3', 'Q5'),
    ('NQ1', 'CN4', 'Q5'),
    ('NQ1', 'CN1', 'Q6'),
    ('NQ1', 'CN2', 'Q6'),
    ('NQ1', 'CN3', 'Q6'),
    ('NQ1', 'CN4', 'Q6'),
    ('NQ1', 'CN1', 'Q7'),
    ('NQ1', 'CN2', 'Q7'),
    ('NQ1', 'CN3', 'Q7'),
    ('NQ1', 'CN4', 'Q7'),
    ('NQ1', 'CN1', 'Q8'),
    ('NQ1', 'CN2', 'Q8'),
    ('NQ1', 'CN3', 'Q8'),
    ('NQ1', 'CN4', 'Q8'),
    ('NQ1', 'CN1', 'Q9'),
    ('NQ1', 'CN2', 'Q9'),
    ('NQ1', 'CN3', 'Q9'),
    ('NQ1', 'CN4', 'Q9'),
    ('NQ1', 'CN1', 'Q10'),
    ('NQ1', 'CN2', 'Q10'),
    ('NQ1', 'CN3', 'Q10'),
    ('NQ1', 'CN4', 'Q10'),
    
    ('NQ2', 'CN1', 'Q1'),
    ('NQ2', 'CN2', 'Q1'),
    ('NQ2', 'CN3', 'Q1'),
    ('NQ2', 'CN4', 'Q1'),
    ('NQ2', 'CN1', 'Q2'),
    ('NQ2', 'CN2', 'Q2'),
    ('NQ2', 'CN3', 'Q2'),
    ('NQ2', 'CN4', 'Q2'),
    ('NQ2', 'CN1', 'Q3'),
    ('NQ2', 'CN2', 'Q3'),
    ('NQ2', 'CN3', 'Q3'),
    ('NQ2', 'CN4', 'Q3'),
    ('NQ2', 'CN1', 'Q4'),
    ('NQ2', 'CN2', 'Q4'),
    ('NQ2', 'CN3', 'Q4'),
    ('NQ2', 'CN4', 'Q4'),
    ('NQ2', 'CN1', 'Q5'),
    ('NQ2', 'CN2', 'Q5'),
    ('NQ2', 'CN3', 'Q5'),
    ('NQ2', 'CN4', 'Q5'),
    ('NQ2', 'CN1', 'Q6'),
    ('NQ2', 'CN2', 'Q6'),
    ('NQ2', 'CN3', 'Q6'),
    ('NQ2', 'CN4', 'Q6'),
    ('NQ2', 'CN1', 'Q7'),
    ('NQ2', 'CN2', 'Q7'),
    ('NQ2', 'CN3', 'Q7'),
    ('NQ2', 'CN4', 'Q7'),
    ('NQ2', 'CN1', 'Q10'),
    ('NQ2', 'CN2', 'Q10'),
    ('NQ2', 'CN3', 'Q10'),
    ('NQ2', 'CN4', 'Q10'),
    
    ('NQ3', 'CN1', 'Q2'),
    ('NQ3', 'CN2', 'Q2'),
    ('NQ3', 'CN4', 'Q2'),
    ('NQ3', 'CN1', 'Q3'),
    ('NQ3', 'CN2', 'Q3'),
    ('NQ3', 'CN4', 'Q3');
    
INSERT INTO
    `payment_methods`(`method_name`)
VALUES
    ('Thanh toán tiền mặt'),
    ('Thanh toán chuyển khoản');

/*Create 10 users: 1 admin, 1 manager, 2 employees, 6 customers
 Default pass: 1234567890 
 Encoded pass: $2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO
 */
 
INSERT INTO
    users (
        id,
        username,
        password,
        email,
        name,
        phone,
        gender,
        image,
        maNhomQuyen,
        status,
        address
    )
VALUES
    (
        1,
        'admin',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'admin@example.com',
        'Admin',
        '0911111232',
        'M',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ1',
        'active',
        '123 Admin St'
    ),
    (
        2,
        'manager',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'manager@example.com',
        'Manager',
        '0948733232',
        'F',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ2',
        'active',
        '456 Manager St'
    ),
    (
        3,
        'employee1',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'employee1@example.com',
        'Employee 1',
        '0911238523',
        'M',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ3',
        'active',
        '789 Employee St'
    ),
    (
        4,
        'employee2',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'employee2@example.com',
        'Employee 2',
        '0911239233',
        'F',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ3',
        'active',
        '012 Employee St'
    ),
    (
        5,
        'customer1',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer1@example.com',
        'Customer 1',
        '0911286233',
        'M',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '345 Customer St'
    ),
    (
        6,
        'customer2',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer2@example.com',
        'Customer 2',
        '0911543233',
        'F',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '678 Customer St'
    ),
    (
        7,
        'customer3',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer3@example.com',
        'Customer 3',
        '0911233233',
        'M',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '901 Customer St'
    ),
    (
        8,
        'customer4',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer4@example.com',
        'Customer 4',
        '0913223232',
        'F',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '234 Customer St'
    ),
    (
        9,
        'customer5',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer5@example.com',
        'Customer 5',
        '0913883434',
        'M',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '567 Customer St'
    ),
    (
        10,
        'customer6',
        '$2y$10$MTfIUUl6vb3fbRYTQi3pYuJzVm0BJVz7yAtOs6850bnQy68Iq7.EO',
        'customer6@example.com',
        'Customer 6',
        '0911432234',
        'F',
        'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp',
        'NQ4',
        'active',
        '890 Customer St'
    );
    
INSERT INTO
    coupons (
        id,
        code,
        quantity,
        percent,
        expired,
        description
    )
VALUES
    (
        1,
        'SALE10',
        100,
        10,
        '2022-12-31',
        'Giảm 10% cho tất cả sản phẩm'
    ),
    (
        2,
        'SALE20',
        100,
        20,
        '2022-12-31',
        'Giảm 20% cho tất cả sản phẩm'
    ),
    (
        3,
        'SALE30',
        100,
        30,
        '2022-12-31',
        'Giảm 30% cho tất cả sản phẩm'
    ),
    (
        4,
        'SALE40',
        100,
        40,
        '2022-12-31',
        'Giảm 40% cho tất cả sản phẩm'
    ),
    (
        5,
        'SALE50',
        100,
        50,
        '2022-12-31',
        'Giảm 50% cho tất cả sản phẩm'
    );
    
    
INSERT INTO nhacungcap (maNCC, ten, diaChi, sdt, email, trangThai)
VALUES 
	('1', 'Nhà cung cấp A', 'Địa chỉ A', '0323456789', 'email1@gmail.com', 1),
	('2', 'Nhà cung cấp B', 'Địa chỉ B', '0987654321', 'email2@gmail.com', 1),
	('3', 'Nhà cung cấp C', 'Địa chỉ C', '0369696969', 'email3@gmail.com', 1),
	('4', 'Nhà cung cấp D', 'Địa chỉ D', '0888888888', 'email4@gmail.com', 1),
	('5', 'Nhà cung cấp E', 'Địa chỉ E', '0777777777', 'email5@gmail.com', 1),
	('6', 'Nhà cung cấp F', 'Địa chỉ F', '0999999999', 'email6@gmail.com', 1),
	('7', 'Nhà cung cấp G', 'Địa chỉ G', '0912345678', 'email7@gmail.com', 1),
	('8', 'Nhà cung cấp H', 'Địa chỉ H', '0966666666', 'email8@gmail.com', 1),
	('9', 'Nhà cung cấp I', 'Địa chỉ I', '0333333333', 'email9@gmail.com', 1),
	('10', 'Nhà cung cấp J', 'Địa chỉ J', '0355555555', 'email10@gmail.com', 1);