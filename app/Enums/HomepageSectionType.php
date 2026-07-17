<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum HomepageSectionType: string implements HasLabel
{
    case FeaturedProducts = 'featured_products';
    case LatestProducts   = 'latest_products';
    case Categories       = 'categories';
    case Offers           = 'offers';
    case Articles         = 'articles';
    case Testimonials     = 'testimonials';
    case Partners         = 'partners';
    case FAQ              = 'faq';
    case ContactUs        = 'contact_us';
    case HeroSection      = 'hero_section';
    case Newsletter       = 'newsletter';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FeaturedProducts => 'المنتجات المميزة',
            self::LatestProducts   => 'أحدث المنتجات',
            self::Categories       => 'الأقسام',
            self::Offers           => 'البنرات الإعلانية (Banners)',
            self::Articles         => 'المقالات',
            self::Testimonials     => 'آراء العملاء',
            self::Partners         => 'شركاؤنا',
            self::FAQ              => 'الأسئلة الشائعة',
            self::ContactUs        => 'تواصل معنا',
            self::HeroSection      => 'السلايدر الرئيسي (Sliders)',
            self::Newsletter       => 'النشرة البريدية',
        };
    }
}
