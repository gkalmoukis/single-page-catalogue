<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant ? $tenant->name : config('app.name') }} | Μενού</title>
    <style>
        :root {
            --primary-color: {{ $tenant && $tenant->primary_color ? $tenant->primary_color : '#000000' }};
            --secondary-color: {{ $tenant && $tenant->secondary_color ? $tenant->secondary_color : '#666666' }};
            --primary-color-light: {{ $tenant && $tenant->primary_color ? $tenant->primary_color . '08' : '#00000008' }};
            --primary-color-dark: {{ $tenant && $tenant->primary_color ? $tenant->primary_color : '#000000' }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--primary-color);
            background: #ffffff;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            background: #ffffff;
        }

        .header {
            text-align: center;
            padding: 60px 0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 60px;
        }

        .logo {
            max-width: 120px;
            max-height: 60px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 300;
            letter-spacing: -0.5px;
        }

        .description {
            font-size: 1.1rem;
            color: var(--secondary-color);
            max-width: 500px;
            margin: 0 auto;
            font-weight: 300;
        }

        .business-info {
            margin-bottom: 80px;
        }

        .info-section {
            margin-bottom: 50px;
        }

        .info-section h3 {
            font-size: 1rem;
            margin-bottom: 30px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--secondary-color);
            padding: 20px 0;
            border-bottom: 1px solid #f8f8f8;
            transition: all 0.2s ease;
        }

        .contact-item:hover {
            color: var(--primary-color);
        }

        .contact-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 400;
        }

        .contact-item a:hover {
            text-decoration: underline;
        }

        .menu-section {
            margin-bottom: 80px;
        }

        .category {
            margin-bottom: 80px;
            background: #ffffff;
        }

        .category-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .category-title {
            font-size: 1.8rem;
            font-weight: 300;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: var(--primary-color);
            letter-spacing: -0.5px;
        }

        .category-emoji {
            font-size: 2rem;
        }

        .category-description {
            color: var(--secondary-color);
            font-size: 1rem;
            font-weight: 300;
        }

        .items {
            padding: 0;
        }

        .item {
            display: flex;
            align-items: flex-start;
            gap: 30px;
            margin-bottom: 50px;
            padding: 30px 0;
            border-bottom: 1px solid #f8f8f8;
            transition: all 0.2s ease;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item:hover {
            background: #fafafa;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 2px;
        }

        .item-content {
            flex: 1;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .item-name {
            font-size: 1.3rem;
            font-weight: 400;
            color: var(--primary-color);
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        .item-description {
            color: var(--secondary-color);
            line-height: 1.6;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tag {
            background: var(--primary-color);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 0;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .icon {
            width: 16px;
            height: 16px;
        }

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 400;
        }

        .social-link:hover {
            text-decoration: underline;
        }

        .opening-hours {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .day-hours {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f8f8f8;
        }

        .day-name {
            font-weight: 500;
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .hours {
            color: var(--secondary-color);
            font-weight: 300;
        }

        .closed {
            color: #cccccc;
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
            color: var(--secondary-color);
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--primary-color);
            font-weight: 300;
        }

        .footer {
            text-align: center;
            padding: 60px 0 40px;
            border-top: 1px solid #f0f0f0;
            margin-top: 80px;
        }

        .footer p {
            color: var(--secondary-color);
            font-size: 0.85rem;
            font-weight: 300;
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 15px;
            }

            .header {
                padding: 40px 0;
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
                gap: 20px;
            }

            .item-header {
                justify-content: flex-start;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .item-image {
                width: 100%;
                height: 200px;
                max-width: 300px;
                align-self: center;
            }
        }
    </style>
</head>
<body>
    @include('restaurant.partials.content', ['tenant' => $tenant, 'categories' => $categories])
</body>
</html>