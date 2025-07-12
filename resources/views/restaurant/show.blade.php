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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark) 100%);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            color: white;
        }

        .logo {
            max-width: 200px;
            max-height: 100px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header .description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
        }

        .business-info {
            background: white;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .info-section {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-section:last-child {
            border-bottom: none;
        }

        .info-section h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
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

        .opening-hours {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .day-hours {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .day-hours:last-child {
            border-bottom: none;
        }

        .day-name {
            font-weight: 500;
            text-transform: capitalize;
            color: var(--primary-color);
        }

        .hours {
            color: var(--secondary-color);
        }

        .closed {
            color: #dc3545;
            font-weight: 500;
        }

        .social-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--primary-color-light);
            border: 1px solid var(--primary-color);
            border-radius: 20px;
            text-decoration: none;
            color: var(--primary-color);
            transition: all 0.2s;
        }

        .social-link:hover {
            background: var(--primary-color);
            color: white;
        }

        .whatsapp-link {
            background: #25D366;
            color: white;
            border-color: #25D366;
        }

        .whatsapp-link:hover {
            background: #128C7E;
            border-color: #128C7E;
        }

        .icon {
            width: 16px;
            height: 16px;
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
            border: 1px solid var(--primary-color-light);
        }

        .category-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark) 100%);
            padding: 20px;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .category-description {
            background: var(--primary-color-light);
            padding: 0 20px 20px;
            color: var(--secondary-color);
            font-size: 0.9rem;
            border-bottom: 1px solid #e9ecef;
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
            gap: 15px;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-image {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            background-color: #f8f9fa;
            border: 2px solid var(--primary-color-light);
        }

        .item-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex: 1;
            gap: 15px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .item-description {
            color: var(--secondary-color);
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
            border-radius: 12px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            font-weight: 500;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-left: 20px;
            background: var(--primary-color-light);
            padding: 4px 12px;
            border-radius: 16px;
            border: 1px solid var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary-color);
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .opening-hours {
                grid-template-columns: 1fr;
            }

            .social-links {
                justify-content: flex-start;
            }

            .item {
                flex-direction: row;
                align-items: flex-start;
                gap: 10px;
            }

            .item-image {
                width: 60px;
                height: 60px;
            }

            .item-content {
                flex-direction: column;
                gap: 8px;
            }

            .item-price {
                margin-left: 0;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            @if($tenant && $tenant->hasLogo())
                <img src="{{ $tenant->logo_url }}" alt="{{ $tenant->name }} Logo" class="logo">
            @endif
            <h1>{{ $tenant ? $tenant->name : config('app.name') }}</h1>
            @if($tenant && $tenant->business_description)
                <p class="description">{{ $tenant->business_description }}</p>
            @else
                <p class="description">{{ __('Φάτε μπρόκολα.') }}</p>
            @endif
        </div>

        @if($tenant)
            <!-- Business Information Section -->
            <div class="business-info">
                <!-- Contact Information -->
                @if($tenant->phone || $tenant->email || $tenant->address)
                    <div class="info-section">
                        <h3>
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Επικοινωνία
                        </h3>
                        <div class="contact-grid">
                            @if($tenant->phone)
                                <div class="contact-item">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <a href="tel:{{ $tenant->phone }}">{{ $tenant->phone }}</a>
                                </div>
                            @endif
                            @if($tenant->email)
                                <div class="contact-item">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <a href="mailto:{{ $tenant->email }}">{{ $tenant->email }}</a>
                                </div>
                            @endif
                            @if($tenant->address)
                                <div class="contact-item">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $tenant->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Opening Hours -->
                @if($tenant->timetable && is_array($tenant->timetable) && count($tenant->timetable) > 0)
                    <div class="info-section">
                        <h3>
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Ωράριο Λειτουργίας
                        </h3>
                        <div class="opening-hours">
                            @foreach(['monday' => 'Δευτέρα', 'tuesday' => 'Τρίτη', 'wednesday' => 'Τετάρτη', 'thursday' => 'Πέμπτη', 'friday' => 'Παρασκευή', 'saturday' => 'Σάββατο', 'sunday' => 'Κυριακή'] as $day => $dayName)
                                @if(isset($tenant->timetable[$day]))
                                    @php
                                        $dayData = $tenant->timetable[$day];
                                        $isClosed = isset($dayData['closed']) && $dayData['closed'];
                                        $hours = isset($dayData['hours']) ? $dayData['hours'] : null;
                                        $open = isset($dayData['open']) ? $dayData['open'] : null;
                                        $close = isset($dayData['close']) ? $dayData['close'] : null;
                                    @endphp
                                    <div class="day-hours">
                                        <span class="day-name">{{ $dayName }}</span>
                                        <span class="{{ $isClosed || strtolower($hours) === 'closed' ? 'closed' : 'hours' }}">
                                            @if($isClosed || strtolower($hours) === 'closed')
                                                Κλειστό
                                            @elseif($hours)
                                                {{ $hours }}
                                            @elseif($open && $close)
                                                {{ $open }} - {{ $close }}
                                            @else
                                                Κλειστό
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Social Links -->
                @if($tenant->social_links && is_array($tenant->social_links) && array_filter($tenant->social_links))
                    <div class="info-section">
                        <h3>
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                            Βρείτε μας
                        </h3>
                        <div class="social-links">
                            @if(!empty($tenant->social_links['website']))
                                <a href="{{ $tenant->social_links['website'] }}" target="_blank" class="social-link">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.148.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                    </svg>
                                    Ιστοσελίδα
                                </a>
                            @endif
                            @if(!empty($tenant->social_links['facebook']))
                                <a href="{{ $tenant->social_links['facebook'] }}" target="_blank" class="social-link">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </a>
                            @endif
                            @if(!empty($tenant->social_links['instagram']))
                                <a href="{{ $tenant->social_links['instagram'] }}" target="_blank" class="social-link">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                    Instagram
                                </a>
                            @endif
                            @if(!empty($tenant->social_links['twitter']))
                                <a href="{{ $tenant->social_links['twitter'] }}" target="_blank" class="social-link">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </a>
                            @endif
                            @if(!empty($tenant->social_links['whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $tenant->social_links['whatsapp']) }}" target="_blank" class="social-link whatsapp-link">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.525 3.508"/>
                                    </svg>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

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
                                        @if($item->hasPhoto())
                                            <img src="{{ $item->getPhotoUrl('medium') }}" alt="{{ $item->name }}" class="item-image">
                                        @else
                                            <div class="item-image"></div>
                                        @endif
                                        <div class="item-content">
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

        <!-- Footer Section -->
        <footer style="text-align: center; padding: 20px; margin-top: 40px; color: #666; font-size: 0.85rem; border-top: 1px solid #e9ecef;">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
            <p style="margin-top: 5px;">Developed by <a href="https://theloom.gr" target="_blank" style="color: #666; text-decoration: none;">theloom.gr</a></p>
        </footer>
    </div>
</body>
</html>