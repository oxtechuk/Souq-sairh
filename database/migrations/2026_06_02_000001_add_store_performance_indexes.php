<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->index('year', 'idx_car_year');
            $table->index('type', 'idx_car_type');
            $table->index('cash_price', 'idx_car_cash_price');
            $table->index('slug', 'idx_car_slug');
            $table->index('is_highlighted', 'idx_car_highlighted');
            $table->index('availability_status', 'idx_car_availability');
            $table->index(['brand_id', 'is_active'], 'idx_car_brand_active');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->index('is_active', 'idx_offer_active');
            $table->index('starts_at', 'idx_offer_starts');
            $table->index('ends_at', 'idx_offer_ends');
            $table->index(['is_active', 'ends_at'], 'idx_offer_active_ends');
        });

        Schema::table('car_images', function (Blueprint $table) {
            $table->index(['car_id', 'type'], 'idx_carimage_car_type');
            $table->index(['car_id', 'sort_order'], 'idx_carimage_car_sort');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index('slug', 'idx_blog_slug');
            $table->index(['is_published', 'published_at'], 'idx_blog_published_at');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index('is_visible', 'idx_testimonial_visible');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->index('sort_order', 'idx_partner_sort');
        });

        Schema::table('calculator_factors', function (Blueprint $table) {
            $table->index('is_active', 'idx_factor_active');
        });

        Schema::table('car_offer', function (Blueprint $table) {
            $table->index(['car_id', 'offer_id'], 'idx_caroffer_car_offer');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index('status', 'idx_lead_status');
            $table->index('source', 'idx_lead_source');
            $table->index('created_at', 'idx_lead_created');
            $table->index('started_at', 'idx_lead_started');
            $table->index(['status', 'created_at'], 'idx_lead_status_created');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['car_id', 'status'], 'idx_booking_car_status');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->index('key', 'idx_setting_key');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex('idx_car_year');
            $table->dropIndex('idx_car_type');
            $table->dropIndex('idx_car_cash_price');
            $table->dropIndex('idx_car_slug');
            $table->dropIndex('idx_car_highlighted');
            $table->dropIndex('idx_car_availability');
            $table->dropIndex('idx_car_brand_active');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex('idx_offer_active');
            $table->dropIndex('idx_offer_starts');
            $table->dropIndex('idx_offer_ends');
            $table->dropIndex('idx_offer_active_ends');
        });

        Schema::table('car_images', function (Blueprint $table) {
            $table->dropIndex('idx_carimage_car_type');
            $table->dropIndex('idx_carimage_car_sort');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('idx_blog_slug');
            $table->dropIndex('idx_blog_published_at');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('idx_testimonial_visible');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex('idx_partner_sort');
        });

        Schema::table('calculator_factors', function (Blueprint $table) {
            $table->dropIndex('idx_factor_active');
        });

        Schema::table('car_offer', function (Blueprint $table) {
            $table->dropIndex('idx_caroffer_car_offer');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('idx_lead_status');
            $table->dropIndex('idx_lead_source');
            $table->dropIndex('idx_lead_created');
            $table->dropIndex('idx_lead_started');
            $table->dropIndex('idx_lead_status_created');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_booking_car_status');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex('idx_setting_key');
        });
    }
};
