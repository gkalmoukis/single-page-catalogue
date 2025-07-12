<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant ? $tenant->name : config('app.name') }} | Μενού</title>
    <style>
        :root {
            --primary-color: {{ $tenant && $tenant->primary_color ? $tenant->primary_color : '#3B82F6' }};
            --secondary-color: {{ $tenant && $tenant->secondary_color ? $tenant->secondary_color : '#6B7280' }};
            --primary-color-light: {{ $tenant && $tenant->primary_color ? $tenant->primary_color . '20' : '#3B82F620' }};
            --primary-color-dark: {{ $tenant && $tenant->primary_color ? 'color-mix(in srgb, ' . $tenant->primary_color . ' 80%, black)' : '#1E40AF' }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            padding: 30px 0;
            border-bottom: 3px solid var(--primary-color);
            margin-bottom: 30px;
            background: linear-gradient(45deg, var(--primary-color-light), rgba(255,255,255,0.9));
            border-radius: 10px;
        }

        .logo {
            max-width: 200px;
            max-height: 100px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .description {
            font-size: 1.1rem;
            color: var(--secondary-color);
            font-style: italic;
            max-width: 600px;
            margin: 0 auto;
        }

        .business-info {
            background: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-color);
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color-light);
            padding-bottom: 8px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--secondary-color);
            padding: 8px;
            background: white;
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .contact-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .contact-item a:hover {
            color: var(--primary-color-dark);
            text-decoration: underline;
        }

        .menu-section {
            margin-bottom: 40px;
        }

        .category {
            margin-bottom: 35px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            border: 1px solid #e2e8f0;
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color-dark));
            color: white;
            padding: 20px;
            text-align: center;
        }

        .category-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .category-emoji {
            font-size: 2rem;
        }

        .category-description {
            opacity: 0.9;
            font-style: italic;
        }

        .items {
            padding: 25px;
        }

        .item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #fafbfc;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .item:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .item-content {
            flex: 1;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .item-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
        }

        .item-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            background: var(--primary-color-light);
            padding: 6px 12px;
            border-radius: 20px;
            border: 2px solid var(--primary-color);
        }

        .item-description {
            color: var(--secondary-color);
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tag {
            background: var(--primary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .icon {
            width: 20px;
            height: 20px;
        }

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            background: var(--primary-color-light);
            transition: all 0.2s;
        }

        .social-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .opening-hours {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .day-hours {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
        }

        .day-name {
            font-weight: 600;
            color: #2d3748;
        }

        .hours {
            color: var(--secondary-color);
        }

        .closed {
            color: #e53e3e;
            font-style: italic;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary-color);
            font-style: italic;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            margin-top: 30px;
            border-top: 3px solid var(--primary-color);
        }

        .footer p {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
                border-radius: 10px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .opening-hours {
                grid-template-columns: 1fr;
            }

            .item {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 15px;
            }

            .item-header {
                justify-content: center;
            }

            .item-image {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    @include('restaurant.partials.content', ['tenant' => $tenant, 'categories' => $categories])
</body>
</html>