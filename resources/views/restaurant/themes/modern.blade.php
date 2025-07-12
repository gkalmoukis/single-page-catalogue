<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant ? $tenant->name : config('app.name') }} | Μενού</title>
    <style>
        :root {
            --primary-color: {{ $tenant && $tenant->primary_color ? $tenant->primary_color : '#FF6B6B' }};
            --secondary-color: {{ $tenant && $tenant->secondary_color ? $tenant->secondary_color : '#4ECDC4' }};
            --primary-color-light: {{ $tenant && $tenant->primary_color ? $tenant->primary_color . '15' : '#FF6B6B15' }};
            --primary-color-dark: {{ $tenant && $tenant->primary_color ? 'color-mix(in srgb, ' . $tenant->primary_color . ' 70%, black)' : '#E85555' }};
            --accent-color: #FFE66D;
            --background-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: var(--background-gradient);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            margin-top: 15px;
            margin-bottom: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .header {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .logo {
            max-width: 180px;
            max-height: 90px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .description {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .business-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 35px;
            border: 2px solid var(--primary-color-light);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            padding: 15px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #555;
            padding: 15px 20px;
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--secondary-color);
        }

        .contact-item:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            border-left-color: var(--primary-color);
        }

        .contact-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .contact-item a:hover {
            color: var(--primary-color-dark);
        }

        .menu-section {
            margin-bottom: 40px;
        }

        .category {
            margin-bottom: 40px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .category:hover {
            border-color: var(--primary-color-light);
            transform: translateY(-5px);
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .category-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .category-emoji {
            font-size: 2.5rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .category-description {
            opacity: 0.95;
            font-size: 1.1rem;
        }

        .items {
            padding: 30px;
        }

        .item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f8fafc 0%, rgba(255,255,255,0.8) 100%);
            border-radius: 18px;
            border: 2px solid transparent;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
        }

        .item:hover {
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            border-color: var(--primary-color-light);
        }

        .item:hover::before {
            width: 8px;
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }

        .item:hover .item-image {
            transform: scale(1.1) rotate(2deg);
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
            font-weight: 700;
            color: #2c3e50;
        }

        .item-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: white;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            padding: 8px 16px;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .item-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 1.05rem;
        }

        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            background: linear-gradient(45deg, var(--accent-color), #ffd93d);
            color: #333;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .icon {
            width: 24px;
            height: 24px;
        }

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 25px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .social-link:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }

        .opening-hours {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .day-hours {
            display: flex;
            justify-content: space-between;
            padding: 12px 18px;
            background: white;
            border-radius: 12px;
            border-left: 4px solid var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .day-name {
            font-weight: 700;
            color: #2c3e50;
        }

        .hours {
            color: #666;
            font-weight: 500;
        }

        .closed {
            color: #e74c3c;
            font-style: italic;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .empty-state h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .footer {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
            border-radius: 15px;
            margin-top: 35px;
            border-top: 3px solid var(--primary-color);
        }

        .footer p {
            color: #666;
            font-size: 0.95rem;
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
                border-radius: 15px;
            }

            .header h1 {
                font-size: 2.2rem;
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
                width: 120px;
                height: 120px;
                align-self: center;
            }
        }
    </style>
</head>
<body>
    @include('restaurant.partials.content', ['tenant' => $tenant, 'categories' => $categories])
</body>
</html>