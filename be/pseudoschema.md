* **User:**
    * **Purpose:** Stores information about registered users on the platform.
    * **Columns:**
        * id: Unique identifier for each user (UUID).
        * username: Unique username chosen by the user (string).
        * password: Encrypted password for user authentication (string).
        * first_name: User's first name (string).
        * last_name: User's last name (string, nullable).
        * cell_phone_number: User's phone number for communication (string).
        * email: Unique email address for user contact (string).
        * banned: Indicates if the user account is banned (boolean, default: false).

* **Admin:**
    * **Purpose:** Defines which users have administrative privileges on the platform.
    * **Columns:**
        * id: Unique identifier for each admin (UUID).
        * user_id: References a User, establishing a one-to-one relationship. This means every admin is also a registered user.

* **Product:**
    * **Purpose:** Stores information about products available for sale on the platform.
    * **Columns:**
        * id: Unique identifier for each product (UUID).
        * name: Name of the product (string).
        * owner_id: References a Shop, indicating which shop owns the product (UUID, nullable).
        * description: Detailed description of the product (text, nullable).
        * price: Price of the product (decimal).
        * category: Category the product belongs to (enum: food, drink, female_fashion, male_fashion, child_fashion, furniture).

* **Shop:**
    * **Purpose:** Stores information about shops selling products on the platform.
    * **Columns:**
        * id: Unique identifier for each shop (UUID).
        * name: Name of the shop (string).
        * owner_id: References a User, indicating the owner of the shop (UUID).

* **Transaction:**
    * **Purpose:** Records a purchase made by a user.
    * **Columns:**
        * id: Unique identifier for each transaction (UUID).
        * customer_id: References a User, indicating the buyer (UUID).

* **Transaction Item:**
    * **Purpose:** Represents individual items purchased within a transaction.
    * **Columns:**
        * id: Unique identifier for each transaction item (UUID).
        * transaction_id: References a Transaction, linking the item to the purchase (UUID).
        * product_id: References a Product, specifying the purchased item (UUID).
        * quantity: Number of units of the product purchased (integer).
        * price: Price per unit of the product at the time of purchase (decimal).

* **Review:**
    * **Purpose:** Stores user reviews and ratings for products.
    * **Columns:**
        * id: Unique identifier for each review (UUID).
        * product_id: References a Product being reviewed (UUID).
        * user_id: References a User who wrote the review (UUID).
        * star: Star rating given by the user (enum: 1, 2, 3, 4, 5).
        * comment: User's written feedback on the product (text, nullable).

* **Review Photo:**
    * **Purpose:** Links photos to reviews, allowing users to visually illustrate their feedback.
    * **Columns:**
        * id: Unique identifier for each review photo (UUID).
        * review_id: References a Review, connecting the photo to the review (UUID).
        * photo_id: References a File, identifying the image (UUID).

* **Wishlist:**
    * **Purpose:** Stores products that users have saved for later purchase.
    * **Columns:**
        * id: Unique identifier for each wishlist item (UUID).
        * product_id: References a Product added to the wishlist (UUID).
        * user_id: References a User who created the wishlist (UUID).

* **File:**
    * **Purpose:** Stores information about files uploaded to the platform, such as product images and review photos.
    * **Columns:**
        * id: Unique identifier for each file (UUID).
        * filename: Name of the file (string).
        * alt: Alternative text description for the image (string, nullable).

* **Product Photo:**
    * **Purpose:** Links photos to products, allowing for visual representation.
    * **Columns:**
        * id: Unique identifier for each product photo (UUID).
        * product_id: References a Product, connecting the photo to the product (UUID).
        * photo_id: References a File, identifying the image (UUID).

**Relationships:**

* **One-to-one:** User - Admin: This relationship defines that a user can be an admin, and each admin must be a registered user.

* **One-to-many:**
    * User - Transaction: A user can have multiple transactions (purchases).
    * User - Wishlist: A user can have multiple wishlist items.
    * User - Review: A user can write multiple reviews.
    * Shop - Product: A shop can have multiple products.
    * Transaction - Transaction Item: A transaction can have multiple items.
    * Review - Review Photo: A review can have multiple photos.
    * Product - Review: A product can have multiple reviews.
    * Product - Product Photo: A product can have multiple photos.

* **Many-to-one:**
    * Transaction Item - Product: A transaction item refers to a specific product.
    * Review Photo - File: A review photo refers to a specific file.
    * Product Photo - File: A product photo refers to a specific file.



