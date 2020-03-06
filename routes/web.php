<?php

use Illuminate\Support\Facades\Route;
use App\Cigarette;

Route::get('/increment', function () {
    Cigarette::create();

    $count = Cigarette::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
    echo now()->format('m/d/y g:i A') . ': ' . $count;
});

Route::get('/decrement', function () {
    Cigarette::orderBy('created_at', 'DESC')->first()->delete();

    $count = Cigarette::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
    echo now()->format('m/d/y g:i A') . ': ' . $count;
});

Route::get('/count', function () {
    $count = Cigarette::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
    echo now()->format('m/d/y') . ': ' . $count;
});

Route::get('/history', function () {
    $counts = Cigarette::whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
        ->orderBy('created_at')
        ->get();

    $counts = $counts->groupBy(function ($item, $key) {
        return $item->updated_at->format('m/d/Y');
    });

    $x = 0;
    foreach($counts as $count) {
        echo $count->first()->created_at->format('m/d/y') . ': ' . $count->count();
        if(($x + 1) !== $counts->count())
            echo "\n";
        $x++;
    }
});
