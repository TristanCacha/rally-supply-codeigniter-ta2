-- Fictional catalog. INSERT IGNORE preserves later edits and stock changes.
USE rally_supply_ta2;

INSERT IGNORE INTO products
  (id, sku, name, category_key, category_label, price_cents, image_file, image_alt, description, badge, option_label, specs_json)
VALUES
(1, 'RALLY-CONTROL', 'The Rally Control', 'paddles', 'Paddles', 489000, 'paddle-control.webp', 'Graphite and cream pickleball paddle with a forest green accent', 'A forgiving all-court paddle with a generous sweet spot and a steady, comfortable feel.', 'PLAYER FAVORITE', 'Grip size', '{"Feel":"Control","Best for":"All-court play","Fit":"Three grip sizes"}'),
(2, 'RALLY-POWER', 'The Rally Power', 'paddles', 'Paddles', 569000, 'paddle-power.webp', 'Modern carbon pickleball paddle ready for the court', 'A slightly head-weighted shape for confident drives, with a textured face for added spin.', 'MORE PUT-AWAY', 'Grip size', '{"Feel":"Power","Best for":"Confident drives","Fit":"Three grip sizes"}'),
(3, 'OUTDOOR-BALLS', 'Rally Pickleballs', 'balls', 'Balls', 69000, 'pickleballs.webp', 'Bright optic-yellow perforated pickleballs', 'Consistent bounce and easy-to-spot color. Choose indoor or outdoor balls; four per pack.', 'COURT ESSENTIAL', 'Ball type', '{"Pack":"Four balls","Visibility":"Optic yellow","Use":"Indoor or outdoor"}'),
(4, 'COURT-DUFFEL', 'The Court Duffel', 'bags', 'Bags', 329000, 'court-duffel.webp', 'Forest green court duffel bag with cream trim', 'Room for paddles, shoes, and a change of clothes, with a quick-access side pocket.', 'READY TO RALLY', 'Color', '{"Storage":"Paddles and shoes","Pocket":"Quick access","Colors":"Forest or Sand"}'),
(5, 'COMFORT-GRIP', 'Comfort Overgrip', 'grips', 'Grips', 39000, 'grip-edge-tape.webp', 'Soft-touch pickleball overgrip rolls in cream and forest green', 'A soft, absorbent overgrip that refreshes your handle and helps keep your hold secure.', 'SMALL UPGRADE', 'Color', '{"Feel":"Soft touch","Use":"Handle refresh","Colors":"Three choices"}'),
(6, 'EDGE-GUARD', 'Paddle Edge Guard', 'protection', 'Edge tape', 45000, 'paddle-edge-tape.webp', 'Protective pickleball paddle edge tape laid out on a studio surface', 'Flexible protective tape helps shield your paddle edge from scrapes and court scuffs.', 'PADDLE CARE', 'Color and width', '{"Purpose":"Edge protection","Width":"Half inch","Finish":"Three choices"}'),
(7, 'BASELINE-SHOES', 'Baseline Court Shoes', 'footwear', 'Court shoes', 429000, 'court-shoes.webp', 'Ivory and forest green indoor court shoes with gum soles', 'Stable lateral support and a grippy non-marking sole for quick changes of direction.', 'COURT READY', 'Size', '{"Support":"Lateral stability","Sole":"Non-marking","Sizes":"US 6 to 11"}'),
(8, 'PERFORMANCE-SET', 'Every Point Performance Set', 'apparel', 'Apparel', 249000, 'court-apparel.webp', 'Folded forest green and warm ivory pickleball performance apparel', 'Lightweight, breathable layers made for warm-ups, long rallies, and the walk home.', 'MOVE FREELY', 'Size', '{"Feel":"Lightweight","Fabric":"Breathable","Sizes":"XS to XL"}'),
(9, 'ACCESSORY-KIT', 'Court Day Accessory Kit', 'accessories', 'Accessories', 159000, 'court-accessories.webp', 'Court day accessories including an ivory towel, green wristbands, and a sage water bottle', 'A quick-dry towel, soft wristbands, and an insulated bottle for the little things between points.', 'THE EXTRAS', 'Set color', '{"Includes":"Towel, bands, bottle","Bottle":"Insulated","Colors":"Sage or Forest"}');

INSERT IGNORE INTO product_variants (product_id, sku, label, stock_qty) VALUES
(1, 'RC-STD', 'Standard grip · 4 1/8 in', 8),
(1, 'RC-SM', 'Small grip · 4 in', 6),
(1, 'RC-LG', 'Large grip · 4 1/4 in', 6),
(2, 'RP-STD', 'Standard grip · 4 1/8 in', 7),
(2, 'RP-SM', 'Small grip · 4 in', 5),
(2, 'RP-LG', 'Large grip · 4 1/4 in', 5),
(3, 'RB-OUT', 'Outdoor · 4-ball pack', 18),
(3, 'RB-IN', 'Indoor · 4-ball pack', 12),
(4, 'CD-FOREST', 'Forest', 7),
(4, 'CD-SAND', 'Sand', 6),
(5, 'CG-FOREST', 'Forest', 20),
(5, 'CG-CREAM', 'Cream', 16),
(5, 'CG-CLAY', 'Clay', 14),
(6, 'EG-BLACK', 'Black · ½ in', 12),
(6, 'EG-CLEAR', 'Clear · ½ in', 12),
(6, 'EG-CLAY', 'Clay · ½ in', 10),
(7, 'BS-6', 'US 6', 4),
(7, 'BS-7', 'US 7', 5),
(7, 'BS-8', 'US 8', 7),
(7, 'BS-9', 'US 9', 7),
(7, 'BS-10', 'US 10', 6),
(7, 'BS-11', 'US 11', 4),
(8, 'PS-XS', 'XS', 6),
(8, 'PS-S', 'S', 8),
(8, 'PS-M', 'M', 8),
(8, 'PS-L', 'L', 8),
(8, 'PS-XL', 'XL', 5),
(9, 'AK-SAGE', 'Sage bottle set', 7),
(9, 'AK-FOREST', 'Forest bottle set', 7);
