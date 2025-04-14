<?php

return [
    'ffmpeg' => [
        'binary' => env('FFMPEG_BINARY', '/usr/bin/ffmpeg'),
        'threads' => env('FFMPEG_THREADS', 12),
        'timeout' => env('FFMPEG_TIMEOUT', 3600),
    ],

    'ffprobe' => [
        'binary' => env('FFPROBE_BINARY', '/usr/bin/ffprobe'),
        'timeout' => env('FFMPEG_TIMEOUT', 3600),
    ],

    'video' => [
        // Video specifications
        'specs' => [
            'min_duration' => 3,
            'max_duration' => 90,
            'width' => 1920,
            'height' => 1080,
            'aspect_ratio' => '9:16',
        ],

        // Encoding settings
        'encoding' => [
            'codec' => 'libx264',
            'preset' => 'medium',
            'crf' => 23,
            'bitrate' => '2000k',
            'audio_bitrate' => '128k',
            'framerate' => 30,
        ],
    ],

    'thumbnail' => [
        'format' => 'jpg',
        'quality' => 90,
        'position' => 1, // seconds from start
    ],
];
