@extends('layouts.landing')

@section('title', 'EasyTix - Pesan Tiket Event Dengan Mudah')

@section('extra-css')
/* ========================================
   HERO SECTION
   ======================================== */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: var(--gradient-hero);
    overflow: hidden;
    padding-top: 80px;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(212,160,23,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.hero-section::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -15%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(30,58,138,0.3) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.hero-content {
    max-width: 600px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 22px;
    background: rgba(212,160,23,0.12);
    border: 1px solid rgba(212,160,23,0.25);
    border-radius: var(--radius-full);
    color: var(--gold-400);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 28px;
    animation: count-up 0.8s ease-out;
}

.hero-badge i {
    animation: pulse-glow 2s infinite;
    border-radius: 50%;
}

.hero-title {
    font-size: clamp(2.5rem, 5.5vw, 4.2rem);
    font-weight: 900;
    color: var(--white);
    margin-bottom: 24px;
    line-height: 1.1;
    letter-spacing: -1px;
}

.hero-title .highlight {
    position: relative;
    display: inline-block;
}

.hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 0;
    right: 0;
    height: 14px;
    background: rgba(212,160,23,0.25);
    border-radius: 4px;
    z-index: -1;
}

.hero-description {
    font-size: 1.15rem;
    color: var(--gray-400);
    line-height: 1.8;
    margin-bottom: 36px;
    max-width: 500px;
}

.hero-buttons {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 50px;
}

.hero-stats {
    display: flex;
    gap: 48px;
}

.hero-stat {
    text-align: left;
}

.hero-stat-value {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 800;
    color: var(--gold-400);
    line-height: 1;
    margin-bottom: 6px;
}

.hero-stat-label {
    font-size: 0.85rem;
    color: var(--gray-500);
    font-weight: 500;
}

/* Hero Visual */
.hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-card-stack {
    position: relative;
    width: 100%;
    max-width: 480px;
    aspect-ratio: 4/3;
}

.hero-ticket-card {
    position: absolute;
    width: 320px;
    background: linear-gradient(145deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: var(--radius-lg);
    padding: 28px;
    box-shadow: var(--shadow-lg);
}

.hero-ticket-card.card-1 {
    top: 0;
    left: 0;
    z-index: 3;
    animation: float 6s ease-in-out infinite;
}

.hero-ticket-card.card-2 {
    top: 60px;
    right: 0;
    z-index: 2;
    animation: float-delayed 7s ease-in-out infinite;
    transform: rotate(5deg);
}

.hero-ticket-card.card-3 {
    bottom: 0;
    left: 40px;
    z-index: 1;
    animation: float 8s ease-in-out infinite;
    animation-delay: 1s;
    transform: rotate(-3deg);
    opacity: 0.7;
}

.ticket-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.ticket-event-type {
    padding: 4px 14px;
    background: rgba(212,160,23,0.2);
    color: var(--gold-400);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ticket-price {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--gold-400);
}

.ticket-title {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 12px;
}

.ticket-info {
    display: flex;
    gap: 20px;
    margin-bottom: 18px;
}

.ticket-info-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--gray-400);
}

.ticket-info-item i {
    color: var(--gold-500);
    font-size: 0.75rem;
}

.ticket-divider {
    border: none;
    border-top: 2px dashed rgba(255,255,255,0.1);
    margin: 16px 0;
}

.ticket-barcode {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.barcode-lines {
    display: flex;
    gap: 3px;
    height: 30px;
}

.barcode-lines span {
    display: block;
    width: 3px;
    background: rgba(255,255,255,0.2);
    border-radius: 1px;
}

.barcode-lines span:nth-child(odd) {
    height: 100%;
}
.barcode-lines span:nth-child(even) {
    height: 70%;
    align-self: flex-end;
}

.ticket-id {
    font-size: 0.7rem;
    color: var(--gray-500);
    font-family: monospace;
    letter-spacing: 2px;
}

/* Decorative Ring */
.hero-ring {
    position: absolute;
    width: 400px;
    height: 400px;
    border: 1px solid rgba(212,160,23,0.1);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: rotate-slow 30s linear infinite;
}

.hero-ring::before {
    content: '';
    position: absolute;
    top: -4px;
    left: 50%;
    width: 8px;
    height: 8px;
    background: var(--gold-400);
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(212,160,23,0.6);
}

/* ========================================
   FEATURES SECTION
   ======================================== */
.features-section {
    position: relative;
    background: var(--navy-800);
    overflow: hidden;
}

.features-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212,160,23,0.3), transparent);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.feature-card {
    text-align: center;
    padding: 44px 32px;
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 28px;
    background: rgba(212,160,23,0.08);
    border: 1px solid rgba(212,160,23,0.15);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--gold-400);
    transition: var(--transition-normal);
}

.feature-card:hover .feature-icon {
    background: var(--gradient-gold);
    color: var(--navy-900);
    transform: scale(1.1) rotate(5deg);
    box-shadow: var(--shadow-gold);
}

.feature-card h3 {
    font-size: 1.3rem;
    color: var(--white);
    margin-bottom: 14px;
}

.feature-card p {
    color: var(--gray-400);
    font-size: 0.95rem;
    line-height: 1.7;
}

/* ========================================
   EVENTS PREVIEW SECTION
   ======================================== */
.events-section {
    position: relative;
    background: var(--navy-900);
    overflow: hidden;
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.event-card {
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--gradient-card);
    border: 1px solid rgba(255,255,255,0.06);
    transition: var(--transition-normal);
    cursor: pointer;
}

.event-card:hover {
    transform: translateY(-10px);
    border-color: rgba(212,160,23,0.3);
    box-shadow: var(--shadow-glow);
}

.event-card-image {
    width: 100%;
    height: 220px;
    position: relative;
    overflow: hidden;
    background: var(--navy-700);
    display: flex;
    align-items: center;
    justify-content: center;
}

.event-card-image .event-placeholder {
    font-size: 4rem;
    color: rgba(212,160,23,0.3);
}

.event-card-image .event-date-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(10,17,40,0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: var(--radius-md);
    padding: 10px 14px;
    text-align: center;
    min-width: 60px;
}

.event-date-badge .date-day {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--gold-400);
    line-height: 1;
}

.event-date-badge .date-month {
    font-size: 0.7rem;
    color: var(--gray-400);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
}

.event-card-image .event-category {
    position: absolute;
    top: 16px;
    left: 16px;
    padding: 6px 16px;
    background: rgba(212,160,23,0.9);
    color: var(--navy-900);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.event-card-body {
    padding: 24px;
}

.event-card-body h3 {
    font-size: 1.2rem;
    color: var(--white);
    margin-bottom: 12px;
    line-height: 1.4;
}

.event-card-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 18px;
}

.event-card-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: var(--gray-500);
}

.event-card-meta span i {
    color: var(--gold-500);
    width: 16px;
    text-align: center;
}

.event-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.event-card-price {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gold-400);
}

.event-card-price small {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 400;
    display: block;
}

.event-card-btn {
    padding: 10px 22px;
    background: rgba(212,160,23,0.15);
    color: var(--gold-400);
    border: 1px solid rgba(212,160,23,0.3);
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
}

.event-card-btn:hover {
    background: var(--gradient-gold);
    color: var(--navy-900);
    border-color: transparent;
}

/* ========================================
   HOW IT WORKS
   ======================================== */
.how-section {
    position: relative;
    background: var(--navy-800);
    overflow: hidden;
}

.how-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212,160,23,0.3), transparent);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    position: relative;
}

.steps-grid::before {
    content: '';
    position: absolute;
    top: 55px;
    left: 15%;
    right: 15%;
    height: 2px;
    background: linear-gradient(90deg, rgba(212,160,23,0.1), rgba(212,160,23,0.4), rgba(212,160,23,0.1));
    z-index: 0;
}

.step-card {
    text-align: center;
    position: relative;
    z-index: 1;
    padding: 0 16px;
}

.step-number {
    width: 70px;
    height: 70px;
    margin: 0 auto 24px;
    background: var(--navy-900);
    border: 2px solid rgba(212,160,23,0.4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--gold-400);
    transition: var(--transition-normal);
}

.step-card:hover .step-number {
    background: var(--gradient-gold);
    color: var(--navy-900);
    border-color: transparent;
    transform: scale(1.15);
    box-shadow: var(--shadow-gold);
}

.step-icon {
    font-size: 2rem;
    color: var(--gold-400);
    margin-bottom: 14px;
    opacity: 0.7;
}

.step-card h3 {
    font-size: 1.15rem;
    color: var(--white);
    margin-bottom: 10px;
}

.step-card p {
    font-size: 0.9rem;
    color: var(--gray-500);
    line-height: 1.7;
}

/* ========================================
   TESTIMONIALS
   ======================================== */
.testimonials-section {
    position: relative;
    background: var(--navy-900);
    overflow: hidden;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.testimonial-card {
    padding: 36px;
}

.testimonial-stars {
    display: flex;
    gap: 4px;
    margin-bottom: 20px;
}

.testimonial-stars i {
    color: var(--gold-400);
    font-size: 0.9rem;
}

.testimonial-text {
    font-size: 1rem;
    color: var(--gray-300);
    line-height: 1.8;
    margin-bottom: 28px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 14px;
}

.testimonial-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--gradient-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1rem;
    color: var(--navy-900);
    flex-shrink: 0;
}

.testimonial-author-info h4 {
    font-size: 1rem;
    color: var(--white);
    font-weight: 600;
    margin-bottom: 2px;
}

.testimonial-author-info p {
    font-size: 0.8rem;
    color: var(--gray-500);
}

/* ========================================
   CTA SECTION
   ======================================== */
.cta-section {
    position: relative;
    padding: 100px 0;
    background: var(--navy-800);
    overflow: hidden;
}

.cta-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 750px;
    margin: 0 auto;
}

.cta-content .cta-badge {
    margin-bottom: 24px;
}

.cta-content h2 {
    font-size: clamp(2rem, 4vw, 3rem);
    color: var(--white);
    margin-bottom: 20px;
}

.cta-content p {
    font-size: 1.15rem;
    color: var(--gray-400);
    margin-bottom: 40px;
    line-height: 1.8;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(212,160,23,0.08) 0%, transparent 60%);
    pointer-events: none;
}

.cta-section::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212,160,23,0.3), transparent);
}

/* ========================================
   RESPONSIVE OVERRIDES FOR LANDING
   ======================================== */
@media (max-width: 1024px) {
    .hero-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }
    .hero-content {
        max-width: 100%;
    }
    .hero-description {
        margin: 0 auto 36px;
    }
    .hero-buttons {
        justify-content: center;
    }
    .hero-stats {
        justify-content: center;
    }
    .hero-visual {
        max-width: 420px;
        margin: 0 auto;
    }
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .events-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .steps-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }
    .steps-grid::before {
        display: none;
    }
    .testimonials-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
    .hero-stats {
        gap: 30px;
    }
    .hero-stat-value {
        font-size: 1.5rem;
    }
    .hero-card-stack {
        transform: scale(0.8);
    }
    .features-grid,
    .events-grid,
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
    .steps-grid {
        grid-template-columns: 1fr;
        max-width: 360px;
        margin: 0 auto;
    }
}

@media (max-width: 480px) {
    .hero-stats {
        flex-direction: column;
        gap: 20px;
        align-items: center;
    }
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    .hero-buttons .btn-landing {
        width: 100%;
        max-width: 300px;
    }
}
@endsection

@section('content')
<!-- ==========================================
     HERO SECTION
     ========================================== -->
<section class="hero-section" id="hero">
    <div class="particles-bg">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container-landing">
        <div class="hero-grid">
            <div class="hero-content" data-aos="fade-right">
                <div class="hero-badge">
                    <i class="fas fa-bolt"></i>
                    Platform Tiket #1 di Indonesia
                </div>
                <h1 class="hero-title">
                    Pesan Tiket <br>
                    <span class="text-gradient-gold highlight">Event Favorit</span><br>
                    Dengan Mudah
                </h1>
                <p class="hero-description">
                    Temukan dan pesan tiket untuk konser, festival, seminar, dan berbagai acara menarik lainnya. Aman, cepat, dan tanpa ribet.
                </p>
                <div class="hero-buttons">
                    <a href="#events" class="btn-landing btn-primary-landing">
                        <i class="fas fa-search"></i>
                        Jelajahi Event
                    </a>
                    <a href="#how-it-works" class="btn-landing btn-secondary-landing">
                        <i class="fas fa-play-circle"></i>
                        Cara Kerja
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat" data-aos="fade-up" data-aos-delay="200">
                        <div class="hero-stat-value" id="stat-events">500+</div>
                        <div class="hero-stat-label">Event Tersedia</div>
                    </div>
                    <div class="hero-stat" data-aos="fade-up" data-aos-delay="400">
                        <div class="hero-stat-value" id="stat-users">50K+</div>
                        <div class="hero-stat-label">Pengguna Aktif</div>
                    </div>
                    <div class="hero-stat" data-aos="fade-up" data-aos-delay="600">
                        <div class="hero-stat-value" id="stat-sold">1M+</div>
                        <div class="hero-stat-label">Tiket Terjual</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual" data-aos="fade-left" data-aos-delay="300">
                <div class="hero-ring"></div>
                <div class="hero-card-stack">
                    <!-- Card 1 - Main -->
                    <div class="hero-ticket-card card-1">
                        <div class="ticket-header">
                            <span class="ticket-event-type">Konser</span>
                            <span class="ticket-price">Rp 350K</span>
                        </div>
                        <div class="ticket-title">Jazz Under The Stars</div>
                        <div class="ticket-info">
                            <div class="ticket-info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>25 Apr 2026</span>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Jakarta</span>
                            </div>
                        </div>
                        <hr class="ticket-divider">
                        <div class="ticket-barcode">
                            <div class="barcode-lines">
                                <span></span><span></span><span></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <span class="ticket-id">ETX-2026-0425</span>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="hero-ticket-card card-2">
                        <div class="ticket-header">
                            <span class="ticket-event-type">Festival</span>
                            <span class="ticket-price">Rp 500K</span>
                        </div>
                        <div class="ticket-title">Bali Music Festival</div>
                        <div class="ticket-info">
                            <div class="ticket-info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>10 Mei 2026</span>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Bali</span>
                            </div>
                        </div>
                        <hr class="ticket-divider">
                        <div class="ticket-barcode">
                            <div class="barcode-lines">
                                <span></span><span></span><span></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <span class="ticket-id">ETX-2026-0510</span>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="hero-ticket-card card-3">
                        <div class="ticket-header">
                            <span class="ticket-event-type">Seminar</span>
                            <span class="ticket-price">Gratis</span>
                        </div>
                        <div class="ticket-title">Tech Summit 2026</div>
                        <div class="ticket-info">
                            <div class="ticket-info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>5 Jun 2026</span>
                            </div>
                            <div class="ticket-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Bandung</span>
                            </div>
                        </div>
                        <hr class="ticket-divider">
                        <div class="ticket-barcode">
                            <div class="barcode-lines">
                                <span></span><span></span><span></span><span></span><span></span>
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <span class="ticket-id">ETX-2026-0605</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     FEATURES SECTION
     ========================================== -->
<section class="features-section section-padding" id="features">
    <div class="container-landing">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> Kenapa EasyTix?</span>
            <h2 class="section-title">Fitur Unggulan <span class="text-gradient-gold">EasyTix</span></h2>
            <p class="section-subtitle">Kami menyediakan pengalaman pemesanan tiket terbaik dengan fitur-fitur canggih yang mempermudah hidup Anda.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Pemesanan Cepat</h3>
                <p>Proses pemesanan tiket hanya dalam hitungan detik. Tanpa antri, tanpa ribet, langsung dapat tiket digital.</p>
            </div>

            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Pembayaran Aman</h3>
                <p>Transaksi Anda dilindungi dengan enkripsi terbaru. Mendukung berbagai metode pembayaran populer.</p>
            </div>

            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3>E-Ticket & QR Code</h3>
                <p>Tiket digital dengan QR Code unik. Cukup tunjukkan dari smartphone Anda saat masuk ke venue.</p>
            </div>

            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3>Notifikasi Real-time</h3>
                <p>Dapatkan notifikasi langsung untuk event baru, promo spesial, dan reminder sebelum acara dimulai.</p>
            </div>

            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Pilih Kursi Interaktif</h3>
                <p>Lihat peta venue secara interaktif dan pilih kursi favorit Anda sebelum orang lain mengambilnya.</p>
            </div>

            <div class="feature-card glass-card" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Customer Support 24/7</h3>
                <p>Tim support kami siap membantu Anda kapan saja. Ada masalah? Kami selalu siap sedia.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     EVENTS SECTION
     ========================================== -->
<section class="events-section section-padding" id="events">
    <div class="container-landing">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-fire"></i> Event Trending</span>
            <h2 class="section-title">Event <span class="text-gradient-gold">Populer</span></h2>
            <p class="section-subtitle">Jangan sampai ketinggalan! Temukan event terpopuler yang sedang trending saat ini.</p>
        </div>

        <div class="events-grid">
            <div class="event-card" data-aos="fade-up" data-aos-delay="100">
                <div class="event-card-image">
                    <i class="fas fa-music event-placeholder"></i>
                    <span class="event-category">Konser</span>
                    <div class="event-date-badge">
                        <div class="date-day">25</div>
                        <div class="date-month">Apr</div>
                    </div>
                </div>
                <div class="event-card-body">
                    <h3>Jazz Under The Stars Concert</h3>
                    <div class="event-card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Istora Senayan, Jakarta</span>
                        <span><i class="fas fa-clock"></i> 19:00 - 23:00 WIB</span>
                    </div>
                    <div class="event-card-footer">
                        <div class="event-card-price">
                            Rp 350K
                            <small>mulai dari</small>
                        </div>
                        <button class="event-card-btn">Beli Tiket</button>
                    </div>
                </div>
            </div>

            <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                <div class="event-card-image">
                    <i class="fas fa-guitar event-placeholder"></i>
                    <span class="event-category">Festival</span>
                    <div class="event-date-badge">
                        <div class="date-day">10</div>
                        <div class="date-month">Mei</div>
                    </div>
                </div>
                <div class="event-card-body">
                    <h3>Bali Music Festival 2026</h3>
                    <div class="event-card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> GWK Cultural Park, Bali</span>
                        <span><i class="fas fa-clock"></i> 15:00 - 00:00 WITA</span>
                    </div>
                    <div class="event-card-footer">
                        <div class="event-card-price">
                            Rp 500K
                            <small>mulai dari</small>
                        </div>
                        <button class="event-card-btn">Beli Tiket</button>
                    </div>
                </div>
            </div>

            <div class="event-card" data-aos="fade-up" data-aos-delay="300">
                <div class="event-card-image">
                    <i class="fas fa-lightbulb event-placeholder"></i>
                    <span class="event-category">Seminar</span>
                    <div class="event-date-badge">
                        <div class="date-day">5</div>
                        <div class="date-month">Jun</div>
                    </div>
                </div>
                <div class="event-card-body">
                    <h3>Tech Summit Indonesia 2026</h3>
                    <div class="event-card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Sasana Budaya, Bandung</span>
                        <span><i class="fas fa-clock"></i> 08:00 - 17:00 WIB</span>
                    </div>
                    <div class="event-card-footer">
                        <div class="event-card-price">
                            Gratis
                            <small>registrasi</small>
                        </div>
                        <button class="event-card-btn">Daftar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     HOW IT WORKS SECTION
     ========================================== -->
<section class="how-section section-padding" id="how-it-works">
    <div class="container-landing">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-magic"></i> Mudah & Cepat</span>
            <h2 class="section-title">Cara Kerja <span class="text-gradient-gold">EasyTix</span></h2>
            <p class="section-subtitle">Hanya 4 langkah mudah untuk mendapatkan tiket event impian Anda.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card" data-aos="fade-up" data-aos-delay="100">
                <div class="step-number">1</div>
                <div class="step-icon"><i class="fas fa-search"></i></div>
                <h3>Cari Event</h3>
                <p>Temukan event impian Anda melalui fitur pencarian dan filter canggih kami.</p>
            </div>

            <div class="step-card" data-aos="fade-up" data-aos-delay="200">
                <div class="step-number">2</div>
                <div class="step-icon"><i class="fas fa-hand-pointer"></i></div>
                <h3>Pilih Tiket</h3>
                <p>Pilih kategori tiket dan jumlah yang diinginkan sesuai budget Anda.</p>
            </div>

            <div class="step-card" data-aos="fade-up" data-aos-delay="300">
                <div class="step-number">3</div>
                <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Bayar Aman</h3>
                <p>Lakukan pembayaran dengan berbagai metode yang aman dan terpercaya.</p>
            </div>

            <div class="step-card" data-aos="fade-up" data-aos-delay="400">
                <div class="step-number">4</div>
                <div class="step-icon"><i class="fas fa-ticket-alt"></i></div>
                <h3>Dapat Tiket</h3>
                <p>E-ticket dengan QR Code langsung dikirim ke email dan tersedia di akun Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     TESTIMONIALS SECTION
     ========================================== -->
<section class="testimonials-section section-padding" id="testimonials">
    <div class="container-landing">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-heart"></i> Testimoni</span>
            <h2 class="section-title">Kata Mereka Tentang <span class="text-gradient-gold">EasyTix</span></h2>
            <p class="section-subtitle">Ribuan pengguna telah mempercayakan pemesanan tiket mereka kepada kami.</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card glass-card" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"EasyTix luar biasa! Saya bisa pesan tiket konser favorit hanya dalam hitungan menit. Prosesnya sangat smooth dan tiketnya langsung masuk email."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AR</div>
                    <div class="testimonial-author-info">
                        <h4>Andi Rahmawan</h4>
                        <p>Mahasiswa</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card glass-card" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Sebagai event organizer, EasyTix membantu kami mengelola penjualan tiket dengan sangat efisien. Dashboard analytics-nya sangat membantu!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SP</div>
                    <div class="testimonial-author-info">
                        <h4>Sari Putri</h4>
                        <p>Event Organizer</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card glass-card" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testimonial-text">"Fitur QR Code-nya keren banget! Nggak perlu lagi print tiket kertas. Tinggal scan dari HP dan langsung masuk. Very convenient!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">BW</div>
                    <div class="testimonial-author-info">
                        <h4>Budi Wicaksono</h4>
                        <p>Software Engineer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     CTA SECTION
     ========================================== -->
<section class="cta-section">
    <div class="container-landing">
        <div class="cta-content" data-aos="zoom-in">
            <span class="section-badge cta-badge"><i class="fas fa-rocket"></i> Mulai Sekarang</span>
            <h2>Siap Untuk Pengalaman<br><span class="text-gradient-gold">Event Terbaik?</span></h2>
            <p>Bergabung dengan 50.000+ pengguna yang sudah merasakan kemudahan EasyTix. Daftar gratis dan mulai jelajahi event-event seru!</p>
            <div class="cta-buttons">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-landing btn-primary-landing">
                        <i class="fas fa-user-plus"></i>
                        Daftar Gratis Sekarang
                    </a>
                @endif
                <a href="#features" class="btn-landing btn-secondary-landing">
                    <i class="fas fa-info-circle"></i>
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-js')
<script>
    // Counter animation for hero stats
    function animateCounter(elementId, target, suffix = '') {
        const element = document.getElementById(elementId);
        if (!element) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let current = 0;
                    const increment = target / 60;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        if (target >= 1000000) {
                            element.textContent = (current / 1000000).toFixed(current >= target ? 0 : 1) + 'M+';
                        } else if (target >= 1000) {
                            element.textContent = Math.floor(current / 1000) + 'K+';
                        } else {
                            element.textContent = Math.floor(current) + suffix;
                        }
                    }, 30);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(element);
    }

    animateCounter('stat-events', 500, '+');
    animateCounter('stat-users', 50000);
    animateCounter('stat-sold', 1000000);

    // Parallax effect on hero particles
    document.addEventListener('mousemove', (e) => {
        const particles = document.querySelectorAll('.particle');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;

        particles.forEach((particle, index) => {
            const speed = (index + 1) * 0.5;
            const x = (mouseX - 0.5) * speed * 30;
            const y = (mouseY - 0.5) * speed * 30;
            particle.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Navbar active link on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-menu a[href^="#"]');

    function setActiveNav() {
        const scrollY = window.pageYOffset;
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            const sectionBottom = sectionTop + section.offsetHeight;
            const sectionId = section.getAttribute('id');
            if (scrollY >= sectionTop && scrollY < sectionBottom) {
                navLinks.forEach(link => {
                    link.style.color = '';
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.style.color = 'var(--gold-400)';
                    }
                });
            }
        });
    }

    window.addEventListener('scroll', setActiveNav);
</script>
@endsection
