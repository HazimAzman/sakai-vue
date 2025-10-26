<?php

namespace app\services;

class DataSanitizer
{
    /**
     * Sanitize product data for public consumption
     */
    public static function sanitizeProduct($product)
    {
        if (!$product) {
            return null;
        }

        return [
            'name' => $product->name ?? '',
            'description' => $product->description ?? '',
            'category' => $product->category ?? '',
            'image_url' => self::generateSecureImageUrl($product->image_path ?? ''),
        ];
    }

    /**
     * Sanitize contact data for public consumption
     */
    public static function sanitizeContact($contact)
    {
        if (!$contact) {
            return null;
        }

        return [
            'name' => $contact->office_name ?? '',
            'address' => $contact->address ?? '',
            'phone' => $contact->phone ?? '',
        ];
    }

    /**
     * Sanitize service data for public consumption
     */
    public static function sanitizeService($service)
    {
        if (!$service) {
            return null;
        }

        return [
            'name' => $service->name ?? '',
            'description' => $service->description ?? '',
            'image_url' => self::generateSecureImageUrl($service->image_path ?? ''),
        ];
    }

    /**
     * Sanitize about data for public consumption
     */
    public static function sanitizeAbout($about)
    {
        if (!$about) {
            return null;
        }

        return [
            'title' => $about->title ?? '',
            'content' => $about->content ?? '',
            'image_url' => self::generateSecureImageUrl($about->image_path ?? ''),
        ];
    }

    /**
     * Sanitize download data for public consumption
     */
    public static function sanitizeDownload($download)
    {
        if (!$download) {
            return null;
        }

        return [
            'name' => $download->brand_name ?? '',
            'description' => $download->description ?? '',
            'file_url' => self::generateSecureFileUrl($download->download_url ?? ''),
            'file_size' => $download->file_size ?? '',
        ];
    }

    /**
     * Sanitize client data for public consumption
     */
    public static function sanitizeClient($client)
    {
        if (!$client) {
            return null;
        }

        return [
            'name' => $client->name ?? '',
            'logo_url' => self::generateSecureImageUrl($client->logo_path ?? ''),
        ];
    }

    /**
     * Sanitize institute data for public consumption
     */
    public static function sanitizeInstitute($institute)
    {
        if (!$institute) {
            return null;
        }

        return [
            'name' => $institute->name ?? '',
            'description' => $institute->description ?? '',
            'logo_url' => self::generateSecureImageUrl($institute->image_path ?? ''),
        ];
    }

    /**
     * Sanitize activity data for public consumption
     */
    public static function sanitizeActivity($activity)
    {
        if (!$activity) {
            return null;
        }

        return [
            'title' => $activity->title ?? '',
            'description' => $activity->description ?? '',
            'date' => $activity->date ?? '',
            'image_url' => self::generateSecureImageUrl($activity->image_path ?? ''),
        ];
    }

    /**
     * Generate secure image URL that doesn't expose internal paths
     */
    private static function generateSecureImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            \Yii::info("Empty image path, returning placeholder", 'data-sanitizer');
            return '/images/placeholder.png';
        }

        // If it's already a public URL, return as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            \Yii::info("Already a public URL: $imagePath", 'data-sanitizer');
            return $imagePath;
        }

        // Convert internal path to public URL
        $publicPath = str_replace(['/backend/web/', 'backend/web/'], '/', $imagePath);
        $publicPath = ltrim($publicPath, '/');
        
        // Ensure it starts with /images/ for security
        if (!str_starts_with($publicPath, 'images/')) {
            $publicPath = 'images/' . basename($publicPath);
        }

        $finalUrl = '/' . $publicPath;
        \Yii::info("Generated image URL - Original: $imagePath, Final: $finalUrl", 'data-sanitizer');
        return $finalUrl;
    }

    /**
     * Generate secure file URL that doesn't expose internal paths
     */
    private static function generateSecureFileUrl($filePath)
    {
        if (empty($filePath)) {
            return null;
        }

        // If it's already a public URL, return as is
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return $filePath;
        }

        // Convert internal path to public URL
        $publicPath = str_replace(['/backend/web/', 'backend/web/'], '/', $filePath);
        $publicPath = ltrim($publicPath, '/');
        
        // Ensure it starts with /catalog/ for security
        if (!str_starts_with($publicPath, 'catalog/')) {
            $publicPath = 'catalog/' . basename($publicPath);
        }

        return '/' . $publicPath;
    }

    /**
     * Sanitize array of items
     */
    public static function sanitizeArray($items, $sanitizerMethod)
    {
        if (!is_array($items)) {
            return [];
        }

        return array_map(function($item) use ($sanitizerMethod) {
            return self::$sanitizerMethod($item);
        }, $items);
    }
}
