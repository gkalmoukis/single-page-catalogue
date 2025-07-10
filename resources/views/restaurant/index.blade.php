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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .header p {
            font-size: 1rem;
            color: #666;
        }

        .catalogue {
            margin-bottom: 40px;
        }

        .category {
            background: white;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .category-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .category-description {
            background: #f8f9fa;
            padding: 0 20px 20px;
            color: #666;
            font-size: 0.9rem;
        }

        .items {
            padding: 20px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .item-description {
            color: #666;
            font-size: 0.9rem;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-left: 20px;
        }

        .cta-section {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.2s ease;
        }

        .cta-button:hover {
            background: #0056b3;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .item {
                flex-direction: column;
                align-items: stretch;
            }

            .item-price {
                margin-left: 0;
                margin-top: 8px;
                text-align: right;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-button {
                width: 200px;
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
                        @if($category->description)
                            <div class="category-description">
                                {{ $category->description }}
                            </div>
                        @endif
                        
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
                                        <div class="item-price">{{ number_format($item->price, 2, ',', '.') }} €</div>
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
                <a href="tel:+1234567890" class="cta-button">Call Us</a>
                <a href="#" class="cta-button">Find Us</a>
                <a href="https://maps.google.com/maps?q=restaurant+near+me" target="_blank" class="cta-button">Get Directions</a>
            </div>
        </div>
    </div>
</body>
</html>