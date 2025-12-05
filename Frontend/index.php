a charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>ULAB Shop — Products</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="topbar">
        <div class="container nav">
            <div class="brand">Shop</div>
            <nav class="menu">
                <a href="index.php">Home</a>
                <a class="active" href="index.php">Products</a>
                <a href="index.php#about">About</a>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            </nav>

        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <h1>Explore Our Products</h1>
            <p>Discover our latest collection at the best prices.</p>

            <div class="filters">
                <label>
                    <span>Category:</span>
                    <select>
                        <option>All</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Home</option>
                    </select>
                </label>

                <label>
                    <span>Sort by:</span>
                    <select>
                        <option>Default</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Rating</option>
                    </select>
                </label>

                <label class="search">
                    <input type="search" placeholder="Search products..." />
                </label>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="grid">

            <article class="card">
                <span class="badge">Electronics</span>
                <div class="thumb">
                    <img src="https://m.media-amazon.com/images/I/61teuID78VL._AC_UF894,1000_QL80_.jpg" alt="Wireless Earbuds" />
                </div>
                <h3>Wireless Earbuds</h3>
                <div class="rating" aria-label="4.5 out of 5 stars">★★★★☆</div>
                <div class="price">$49.99</div>
                <button class="btn">Add to Cart</button>
            </article>

            <article class="card">
                <span class="badge">Fashion</span>
                <div class="thumb">
                    <img src="https://img.kwcdn.com/product/fancy/466d458a-089b-4be3-97a9-b008b6c2e260.jpg?imageMogr2/auto-orient%7CimageView2/2/w/800/q/70/format/webp" alt="Stylish T-Shirt" />
                </div>
                <h3>Stylish T‑Shirt</h3>
                <div class="rating" aria-label="4 out of 5 stars">★★★★☆</div>
                <div class="price">$29.99</div>
                <button class="btn">Add to Cart</button>
            </article>

            <article class="card">
                <span class="badge">Home</span>
                <div class="thumb">
                    <img src="https://assets.weimgs.com/weimgs/rk/images/wcm/products/202540/0361/ribbed-glass-table-lamp-9-18-1-o.jpg" alt="Table Lamp" />
                </div>
                <h3>Table Lamp</h3>
                <div class="rating" aria-label="4 out of 5 stars">★★★★☆</div>
                <div class="price">$59.99</div>
                <button class="btn">Add to Cart</button>
            </article>

            <article class="card">
                <span class="badge">Electronics</span>
                <div class="thumb">
                    <img src="https://ng.jumia.is/unsafe/fit-in/500x500/filters:fill(white)/product/08/1869942/1.jpg?6935" alt="Smart Watch" />
                </div>
                <h3>Smart Watch</h3>
                <div class="rating" aria-label="5 out of 5 stars">★★★★★</div>
                <div class="price">$99.99</div>
                <button class="btn">Add to Cart</button>
            </article>

            <article class="card">
                <span class="badge">Fashion</span>
                <div class="thumb">
                    <img src="https://hips.hearstapps.com/vader-prod.s3.amazonaws.com/1725966879-mango-wide-leg-jeans-66e02a10419f1.jpg?crop=1.00xw:0.895xh;0,0.0887xh&resize=980:*" alt="Denim Jeans" />
                </div>
                <h3>Denim Jeans</h3>
                <div class="rating" aria-label="4 out of 5 stars">★★★★☆</div>
                <div class="price">$39.99</div>
                <button class="btn">Add to Cart</button>
            </article>

        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <p>© 2025 ULAB Shop. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>