<?php

use App\{Models\User,
    Models\Settings,
    Models\Currency,
    Models\Category,
    Models\Block,
    Enums\StatusEnum,};
use Illuminate\Support\{Facades\Cache, Arr, Facades\Storage};
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists(function: 'render_table_image')) {
    function render_table_image(string $path): string
    {
        return !empty($path) ? '<img src="'.$path.'" class="img-thumbnail rounded-2" style="max-height: 60px; width:60px">' : '';
    }
}

if (!function_exists('get_languages')) {
    function get_languages()
    {
        return Cache::remember('appLanguages', 3600, function () {
            return \App\Models\Language::all();
        });
    }
}

if (!function_exists('get_all_settings')) {
    function get_all_settings(): array
    {
        return Cache::rememberForever('all_settings', function () {
            return Settings::pluck('value', 'key')->toArray();
        });
    }
}

if (!function_exists('get_setting')) {
    function get_setting(string $key, $default = null): mixed
    {
        $settings = get_all_settings();

        if (isset($settings[$key])) {
            return is_image($settings[$key]) ? get_full_image_url($settings[$key]) : $settings[$key];
        }

        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = Settings::where('key', $key)->first();

            if ($setting) {
                $value = $setting->value;
                return is_image($value) ? get_full_image_url($value) : $value;
            }

            return $default;
        });
    }
}

if (!function_exists('update_setting')) {
    function update_setting(string $key, $value): void
    {
        Settings::updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget('all_settings');
        get_all_settings();
    }
}

if (!function_exists('default_currency')) {
    function default_currency()
    {
        return Cache::rememberForever('default_currency', function () {
            $defaultCurrencyId = get_setting('site_currency_id');

            if ($defaultCurrencyId) {
                return Currency::find($defaultCurrencyId);
            }

            return null;
        });
    }
}

if (!function_exists('is_image')) {
    function is_image(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $googlePattern = '/^https?:\/\/[a-z0-9\-]*(\.googleusercontent\.com\/)/i';
            $applePattern = '/^https?:\/\/[a-z0-9\-]*(\.apple\.com\/)/i';

            if (preg_match($googlePattern, $value) || preg_match($applePattern, $value)) {
                return true;
            }
        }

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $extension = pathinfo($value, PATHINFO_EXTENSION);

        return in_array(strtolower($extension), $imageExtensions);
    }
}

if (!function_exists('is_video')) {
    function is_video(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $youtubePattern = '/^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/.+/i';
            $vimeoPattern = '/^https?:\/\/(www\.)?vimeo\.com\/.+/i';
            $tiktokPattern = '/^https?:\/\/(www\.)?tiktok\.com\/.+/i';

            if (preg_match($youtubePattern, $value) ||
                preg_match($vimeoPattern, $value) ||
                preg_match($tiktokPattern, $value)) {
                return true;
            }
        }

        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', 'mpeg', 'mpg'];
        $extension = pathinfo($value, PATHINFO_EXTENSION);

        return in_array(strtolower($extension), $videoExtensions);
    }
}

if (!function_exists('get_timezones')) {
    function get_timezones(): array
    {
        $timezones = [];
        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $timezones[$timezone] = $timezone;
        }
        return $timezones;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, string $folder, string $disk = 'spaces', string $oldFile = null): ?string
    {
        try {
            if (empty($file)) {
                return null;
            }
            if (is_string($file) && file_exists($file)) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = Str::random(40) . '.' . $extension;
                $path = trim($folder, '/') . '/' . $filename;

                $options = [];
                if (in_array($disk, ['spaces', 's3'])) {
                    $options = [
                        'visibility' => 'public',
                        'ContentType' => mime_content_type($file),
                        'ACL' => 'public-read',
                        'CacheControl' => 'max-age=31536000'
                    ];
                }

                Storage::disk($disk)->put($path, file_get_contents($file), $options);

                return $path;
            }
            else if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;

                return $file->storeAs(
                    $folder,
                    $filename,
                    [
                        'disk' => $disk,
                        'visibility' => 'public'
                    ]
                );
            }
            else {
                throw new \InvalidArgumentException('Invalid file input');
            }

        } catch (Exception $e) {
            throw new \RuntimeException('Failed to upload file: ' . $e->getMessage());
        }
    }
}

if (!function_exists('upload_large_file')) {
    function upload_large_file($file, string $folder, string $disk = 'spaces', string $oldFile = null, int $chunkSize = 5 * 1024 * 1024): string
    {
        try {
            if (is_string($file) && file_exists($file)) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename = uniqid() . '_' . time() . '.' . $extension;
                $path = trim($folder, '/') . '/' . $filename;
                $mimeType = mime_content_type($file);
                $stream = fopen($file, 'r');
            }
            else if ($file instanceof \Illuminate\Http\UploadedFile) {
                $extension = $file->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;
                $path = trim($folder, '/') . '/' . $filename;
                $mimeType = $file->getMimeType();
                $stream = fopen($file->getRealPath(), 'r');
            }
            else {
                throw new \InvalidArgumentException('Invalid file input');
            }

            $upload = Storage::disk($disk)->getClient()->upload(
                config('filesystems.disks.'.$disk.'.bucket'),
                $path,
                $stream,
                'public-read',
                [
                    'mup_threshold' => $chunkSize,
                    'params' => [
                        'ACL' => 'public-read',
                        'ContentType' => $mimeType,
                        'CacheControl' => 'max-age=31536000'
                    ]
                ]
            );

            fclose($stream);
            return $path;

        } catch (Exception $e) {
            throw new \RuntimeException('Failed to upload large file: ' . $e->getMessage());
        }
    }
}

if (!function_exists('get_file_url')) {
    function get_file_url(?string $path, string $disk = 'spaces'): ?string
    {
        if (empty($path)) {
            return null;
        }
        return $disk === 'public' ? asset(Storage::url($path)) : config('filesystems.disks.'.$disk.'.url').'/'.$path;
    }
}

if (!function_exists('get_file_path')) {

    function get_file_path(string $filePath, string $disk = 'public'): string
    {
        if (!Storage::disk($disk)->exists($filePath)) {
            throw new \RuntimeException("File does not exist on disk '$disk': $filePath");
        }

        return Storage::disk($disk)->path($filePath);
    }
}

if (!function_exists('delete_file')) {
    function delete_file(string $fieldName, string $disk = 'public'): void
    {
        if ($fieldName) {
            if (Storage::disk($disk)->exists($fieldName)) {
                Storage::disk($disk)->delete($fieldName);
            }
        }
    }
}

if (!function_exists('get_full_image_url')) {
    function get_full_image_url(?string $relativePath, string $disk = 'spaces'): string
    {
        if (empty($relativePath)) {
            return '';
        }

        if (filter_var($relativePath, FILTER_VALIDATE_URL)) {
            return $relativePath;
        }

        return $disk === 'public' ? asset(Storage::url($relativePath)) : config('filesystems.disks.'.$disk.'.url').'/'.$relativePath;
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            $format = get_setting('site_date_format', 'Y-m-d');
            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('format_time')) {
    function format_time(?string $time): ?string
    {
        if (!$time) {
            return null;
        }

        try {
            $format = get_setting('site_time_format', 'H:i');
            return Carbon::parse($time)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime(?string $datetime): ?string
    {
        if (!$datetime) {
            return null;
        }

        try {
            $dateFormat = get_setting('site_date_format', 'Y-m-d');
            $timeFormat = get_setting('site_time_format', 'H:i');

            $format = "{$dateFormat} {$timeFormat}";
            return Carbon::parse($datetime)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, $html = false): string
    {
        $currency = default_currency();
        $currencyPosition = get_setting('site_currency_position');
        $currencyPrecision = get_setting('site_precision', 2);

        if ($currency) {
            $formattedAmount = number_format($amount, $currencyPrecision);

            if ($html) {
                if ($currencyPosition === 'left') {
                    return "<span class='currency'>{$currency->symbol}</span> <span class='price_number mx-1'>{$formattedAmount}</span>";
                } elseif ($currencyPosition === 'right') {
                    return "<span class='price_number mx-1'>{$formattedAmount}</span> <span class='currency'>{$currency->symbol}</span>";
                }
            } else {
                if ($currencyPosition === 'left') {
                    return $currency->symbol . ' ' . $formattedAmount;
                } elseif ($currencyPosition === 'right') {
                    return $formattedAmount . ' ' . $currency->symbol;
                }
            }
        }

        return number_format($amount, $currencyPrecision);
    }
}


if (!function_exists('localized_url')) {
    function localized_url($locale, $routeName, $parameters = [])
    {
        if ($locale === config('app.locale')) {
            return route($routeName, $parameters);
        }

        return route($routeName, array_merge(['locale' => $locale], $parameters));
    }
}

if (!function_exists('slugify')) {
    function slugify($string, $model = null)
    {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $string);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = strtolower($slug);

        $count = $model::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        return $slug;
    }
}

if (!function_exists('str_limit')) {
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return rtrim(mb_substr($value, 0, $limit)) . $end;
    }
}

if (!function_exists('get_blocked_user_ids')) {
    function get_blocked_user_ids(): array
    {
        $userId = auth()->id();

        if (!$userId) {
            return [];
        }

        return Cache::remember("user_{$userId}_blocked_ids", 600, function() use ($userId) {
            return Block::where('user_id', $userId)
                ->orWhere('blocked_user_id', $userId)
                ->get()
                ->reduce(function ($carry, $block) use ($userId) {
                    if ($block->user_id === $userId) {
                        $carry[] = $block->blocked_user_id;
                    } else {
                        $carry[] = $block->user_id;
                    }
                    return $carry;
                }, []);
        });
    }
}




