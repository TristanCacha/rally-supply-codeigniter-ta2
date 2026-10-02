-- Optional POS extension. The required TA2 customers and users tables stay unchanged.
USE rally_supply_ta2;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(40) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  category_key VARCHAR(30) NOT NULL,
  category_label VARCHAR(40) NOT NULL,
  price_cents INT UNSIGNED NOT NULL,
  image_file VARCHAR(100) NOT NULL,
  image_alt VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  badge VARCHAR(50) NOT NULL,
  option_label VARCHAR(40) NOT NULL,
  specs_json TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_variants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  sku VARCHAR(50) NOT NULL UNIQUE,
  label VARCHAR(60) NOT NULL,
  stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_variant_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS staff_credentials (
  user_id INT PRIMARY KEY,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_staff_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NULL,
  user_id INT NOT NULL,
  total_cents INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_sale_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
  CONSTRAINT fk_sale_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sale_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  variant_id INT NOT NULL,
  product_name VARCHAR(100) NOT NULL,
  variant_label VARCHAR(60) NOT NULL,
  unit_price_cents INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  line_total_cents INT UNSIGNED NOT NULL,
  CONSTRAINT fk_item_sale FOREIGN KEY (sale_id) REFERENCES sales(id),
  CONSTRAINT fk_item_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id)
) ENGINE=InnoDB;

-- Website orders are separate from staff-recorded POS sales. They do not
-- change the two required TA2 account tables or require a staff login.
CREATE TABLE IF NOT EXISTS web_orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  public_token CHAR(48) NOT NULL UNIQUE,
  buyer_name VARCHAR(100) NOT NULL,
  buyer_email VARCHAR(100) NOT NULL,
  buyer_phone VARCHAR(20) NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  total_cents INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS web_order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  variant_id INT NOT NULL,
  product_name VARCHAR(100) NOT NULL,
  variant_label VARCHAR(60) NOT NULL,
  unit_price_cents INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  line_total_cents INT UNSIGNED NOT NULL,
  CONSTRAINT fk_web_item_order FOREIGN KEY (order_id) REFERENCES web_orders(id),
  CONSTRAINT fk_web_item_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id)
) ENGINE=InnoDB;
