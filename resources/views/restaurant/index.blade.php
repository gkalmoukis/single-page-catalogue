<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Name – Discover Our Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .catalogue {
            margin-bottom: 50px;
        }

        .category {
            background: white;
            margin-bottom: 40px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .category-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px 30px;
            font-size: 1.8rem;
            font-weight: 500;
        }

        .items {
            padding: 30px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .item-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
        }

        .item-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #e74c3c;
            margin-left: 20px;
        }

        .cta-section {
            text-align: center;
            padding: 50px 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .item {
                flex-direction: column;
                align-items: stretch;
            }

            .item-price {
                margin-left: 0;
                margin-top: 10px;
                text-align: right;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-button {
                width: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Welcome to Our Restaurant</h1>
            <p>Serving delicious food made with love.</p>
        </div>

        <!-- Catalogue Section -->
        <div class="catalogue">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <div class="category">
                        <div class="category-header">
                            @if($category->emoji)
                                {{ $category->emoji }}
                            @endif
                            {{ $category->name }}
                        </div>
                        
                        @if($category->items->count() > 0)
                            <div class="items">
                                @foreach($category->items as $item)
                                    <div class="item">
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="item-description">{{ $item->description }}</div>
                                            @endif
                                        </div>
                                        <div class="item-price">€{{ number_format($item->price, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="items">
                                <div class="empty-state">
                                    <p>No items available in this category yet.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <h3>Menu Coming Soon!</h3>
                    <p>We're preparing our delicious menu for you. Please check back soon!</p>
                </div>
            @endif
        </div>

        <!-- Call-to-Actions Section -->
        <div class="cta-section">
            <div class="cta-buttons">
                <a href="tel:+1234567890" class="cta-button">📞 Call Us</a>
                <a href="#" class="cta-button">📍 Find Us</a>
                <a href="https://maps.google.com/maps?q=restaurant+near+me" target="_blank" class="cta-button">🗺 Get Directions</a>
            </div>
        </div>
    </div>
</body>
</html>