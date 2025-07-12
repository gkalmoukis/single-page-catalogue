<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant ? $tenant->name : config('app.name') }} | Μενού</title>
    <style>
        :root {
            --primary-color: {{ $tenant && $tenant->primary_color ? $tenant->primary_color : '#2D3748' }};
            --secondary-color: {{ $tenant && $tenant->secondary_color ? $tenant->secondary_color : '#718096' }};
            --primary-color-light: {{ $tenant && $tenant->primary_color ? $tenant->primary_color . '10' : '#2D374810' }};
            --primary-color-dark: {{ $tenant && $tenant->primary_color ? 'color-mix(in srgb, ' . $tenant->primary_color . ' 85%, black)' : '#1A202C' }};
            --gold-accent: #D4AF37;
            --cream-bg: #FAF7F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Playfair Display', 'Times New Roman', serif;
            line-height: 1.7;
            color: #2d3748;
            background: linear-gradient(to bottom, var(--cream-bg) 0%, #f7f5f3 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background: #ffffff;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
            border-radius: 0;
            margin-top: 0;
            margin-bottom: 0;
            position: relative;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-accent) 0%, var(--primary-color) 50%, var(--gold-accent) 100%);
        }

        .header {
            text-align: center;
            padding: 50px 30px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 40px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--gold-accent);
        }

        .logo {
            max-width: 180px;
            max-height: 90px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .header h1 {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 400;
            letter-spacing: -1px;
            position: relative;
        }

        .description {
            font-size: 1.3rem;
            color: var(--secondary-color);
            font-style: italic;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .business-info {
            background: var(--cream-bg);
            padding: 40px;
            border-radius: 0;
            margin-bottom: 50px;
            border: 1px solid #e8e5e0;
            position: relative;
        }

        .business-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 40px;
            right: 40px;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, var(--gold-accent) 50%, transparent 100%);
        }

        .info-section {
            margin-bottom: 35px;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .info-section h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--secondary-color);
            padding: 15px 0;
            border-bottom: 1px dotted #d1d5db;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            color: var(--primary-color);
            padding-left: 10px;
        }

        .contact-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .contact-item a:hover {
            color: var(--gold-accent);
        }

        .menu-section {
            margin-bottom: 50px;
        }

        .category {
            margin-bottom: 50px;
            background: #ffffff;
            border: 1px solid #e8e5e0;
            position: relative;
        }

        .category-header {
            background: var(--primary-color);
            color: #ffffff;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .category-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid var(--primary-color);
        }

        .category-title {
            font-size: 2.2rem;
            font-weight: 400;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            letter-spacing: -0.5px;
        }

        .category-emoji {
            font-size: 2.5rem;
        }

        .category-description {
            opacity: 0.9;
            font-style: italic;
            font-size: 1.1rem;
        }

        .items {
            padding: 40px;
        }

        .item {
            display: flex;
            align-items: flex-start;
            gap: 25px;
            margin-bottom: 35px;
            padding: 25px 0;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item:hover {
            padding-left: 15px;
            background: var(--cream-bg);
            margin-left: -15px;
            margin-right: -15px;
            padding-right: 15px;
        }

        .item-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            border: 3px solid #ffffff;
        }

        .item-content {
            flex: 1;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .item-name {
            font-size: 1.4rem;
            font-weight: 500;
            color: var(--primary-color);
            line-height: 1.3;
        }

        .item-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gold-accent);
            font-family: 'Georgia', serif;
        }

        .item-description {
            color: var(--secondary-color);
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            background: var(--primary-color);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 0;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid var(--primary-color);
        }

        .icon {
            width: 20px;
            height: 20px;
        }

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            text-decoration: none;
            padding: 12px 0;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .social-link:hover {
            color: var(--gold-accent);
            border-bottom-color: var(--gold-accent);
        }

        .opening-hours {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .day-hours {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dotted #d1d5db;
        }

        .day-name {
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .hours {
            color: var(--secondary-color);
            font-weight: 400;
        }

        .closed {
            color: #a0a0a0;
            font-style: italic;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary-color);
        }

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-weight: 400;
        }

        .footer {
            text-align: center;
            padding: 30px;
            background: var(--cream-bg);
            margin-top: 50px;
            border-top: 1px solid #e8e5e0;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 1px;
            background: var(--gold-accent);
        }

        .footer p {
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-style: italic;
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 2.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .opening-hours {
                grid-template-columns: 1fr;
            }

            .item {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .item-header {
                justify-content: center;
                text-align: center;
            }

            .item-image {
                width: 100px;
                height: 100px;
                align-self: center;
            }
        }
    </style>
</head>
<body>
    @include('restaurant.partials.content', ['tenant' => $tenant, 'categories' => $categories])
</body>
</html>