-- users
CREATE TABLE users (
    id uuid PRIMARY KEY,
    username varchar UNIQUE NOT NULL,
    password varchar NOT NULL,
    first_name varchar,
    last_name varchar,
    cell_phone_number varchar,
    email varchar UNIQUE,
    banned boolean DEFAULT false
);

-- admins
CREATE TABLE admins (
    user_id uuid PRIMARY KEY REFERENCES users(id),
);

-- products
CREATE TABLE products (
    id uuid PRIMARY KEY,
    name varchar NOT NULL,
    owner_id uuid REFERENCES shops(id),
    description text,
    price decimal NOT NULL,
    category enum ('food', 'drink', 'female_fashion', 'male_fashion', 'child_fashion', 'furniture')
);

-- shops
CREATE TABLE shops (
    id uuid PRIMARY KEY,
    name varchar NOT NULL,
    owner_id uuid REFERENCES users(id)
);

-- transactions
CREATE TABLE transactions (
    id uuid PRIMARY KEY,
    customer_id uuid REFERENCES users(id)
);

-- transaction_items
CREATE TABLE transaction_items (
    id uuid PRIMARY KEY,
    transaction_id uuid REFERENCES transactions(id),
    product_id uuid REFERENCES products(id),
    quantity int NOT NULL,
    price decimal NOT NULL
);

-- reviews
CREATE TABLE reviews (
    id uuid PRIMARY KEY,
    product_id uuid REFERENCES products(id),
    user_id uuid REFERENCES users(id),
    star enum (1, 2, 3, 4, 5) NOT NULL,
    comment text
);

-- review_photos
CREATE TABLE review_photos (
    id uuid PRIMARY KEY,
    review_id uuid REFERENCES reviews(id),
    photo_id uuid REFERENCES files(id)
);

-- wishlists
CREATE TABLE wishlists (
    id uuid PRIMARY KEY,
    product_id uuid REFERENCES products(id),
    user_id uuid REFERENCES users(id)
);

-- files
CREATE TABLE files (
    id uuid PRIMARY KEY,
    filename varchar NOT NULL,
    alt varchar
);

-- product_photos
CREATE TABLE product_photos (
    id uuid PRIMARY KEY,
    product_id uuid REFERENCES products(id),
    photo_id uuid REFERENCES files(id)
);
