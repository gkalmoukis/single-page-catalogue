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
                        @foreach(['Δευτέρα' => 'monday', 'Τρίτη' => 'tuesday', 'Τετάρτη' => 'wednesday', 'Πέμπτη' => 'thursday', 'Παρασκευή' => 'friday', 'Σάββατο' => 'saturday', 'Κυριακή' => 'sunday'] as $dayName => $dayKey)
                            @if(isset($tenant->timetable[$dayKey]))
                                @php
                                    $dayData = $tenant->timetable[$dayKey];
                                    $hours = is_array($dayData) ? ($dayData['hours'] ?? '') : $dayData;
                                    $isClosed = is_array($dayData) ? ($dayData['closed'] ?? false) : false;
                                    $open = is_array($dayData) ? ($dayData['open'] ?? '') : '';
                                    $close = is_array($dayData) ? ($dayData['close'] ?? '') : '';
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
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                        </svg>
                        Βρείτε μας
                    </h3>
                    <div class="social-links">
                        @if(!empty($tenant->social_links['website']))
                            <a href="{{ $tenant->social_links['website'] }}" target="_blank" class="social-link">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
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
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.618 5.367 11.986 11.988 11.986s11.987-5.368 11.987-11.986C24.014 5.367 18.635.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.611-3.193-1.559l3.193-3.193 3.193 3.193c-.745.948-1.896 1.559-3.193 1.559z"/>
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
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                </svg>
                                WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Menu Section -->
        <div class="menu-section">
            @if($categories && $categories->count() > 0)
                @foreach($categories as $category)
                    <div class="category">
                        <div class="category-header">
                            <div class="category-title">
                                @if($category->emoji)
                                    <span class="category-emoji">{{ $category->emoji }}</span>
                                @endif
                                {{ $category->name }}
                            </div>
                            @if($category->description)
                                <div class="category-description">{{ $category->description }}</div>
                            @endif
                        </div>

                        <div class="items">
                            @if($category->items && $category->items->count() > 0)
                                @foreach($category->items->where('is_active', true)->sortBy('sort_order') as $item)
                                    <div class="item">
                                        @if($item->getFirstMediaUrl('photo'))
                                            <img src="{{ $item->getFirstMediaUrl('photo', 'thumb') }}" alt="{{ $item->name }}" class="item-image">
                                        @endif
                                        
                                        <div class="item-content">
                                            <div class="item-header">
                                                <h4 class="item-name">{{ $item->name }}</h4>
                                                @if($item->price)
                                                    <span class="item-price">€{{ number_format($item->price, 2) }}</span>
                                                @endif
                                            </div>
                                            
                                            @if($item->description)
                                                <p class="item-description">{{ $item->description }}</p>
                                            @endif
                                            
                                            @if($item->tags && $item->tags->count() > 0)
                                                <div class="item-tags">
                                                    @foreach($item->tags as $tag)
                                                        <span class="tag" style="background-color: {{ $tag->color ?? 'var(--primary-color)' }}">{{ $tag->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <h3>Δεν υπάρχουν προϊόντα</h3>
                                    <p>Αυτή η κατηγορία δεν έχει ακόμη προϊόντα.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <h3>Δεν υπάρχουν κατηγορίες</h3>
                    <p>Δεν έχουν προστεθεί ακόμη κατηγορίες στο μενού.</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $tenant ? $tenant->name : config('app.name') }}. Όλα τα δικαιώματα διατηρούνται.</p>
        <p style="margin-top: 5px;">Developed by <a href="https://theloom.gr" target="_blank" style="color: #666; text-decoration: none;">theloom.gr</a></p>
    </div>
</div>