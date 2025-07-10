<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Μενού | {{ config('app.name') }}</title>
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

        .item-tags {
            margin-top: 5px;
        }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            margin: 2px 4px 2px 0;
            font-size: 0.75rem;
            color: white;
            border-radius: 12px;
            font-weight: 500;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-left: 20px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>{{ __('Kitchen @ THE LOOM SFT') }}</h1>
            <p>{{ __('Φάτε μπρόκολα.') }}</p>
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
                                            @if($item->tags && $item->tags->count() > 0)
                                                <div class="item-tags">
                                                    @foreach($item->tags as $tag)
                                                        <span class="tag" style="background-color: {{ $tag->color }};">{{ $tag->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="item-price">{{ number_format($item->price, 2, ',', '.') }} €</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="items">
                                <div class="empty-state">
                                    <p>{{ __('Δεν υπάρχουν προϊόντα σε αυτή την κατηγορία ακόμα.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <h3>{{ __('Ετοιμάζουμε το νόστιμο μενού μας για εσάς.') }}</h3>
                    <p>{{ __(' Παρακαλούμε ελέγξτε ξανά σύντομα!') }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>