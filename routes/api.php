<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/categories', function (Request $request) {
        if (!in_array($request->query('type'), ['events', 'transactions', 'notes'])) {
            return collect();
        }
        return $request->user()->categories->where('type', $request->query('type'));
    });

    Route::get('/flats', function (Request $request) {
        if (!$request->query('with')) {
            return $request->user()->flats;
        }
        return $request->user()->flats->load(explode(',', $request->query('with')));
    });

    Route::get('/flats/{flat}', function (Request $request, \App\Flat $flat) {
        if (!$flat->participants->contains($request->user()->id)) {
            return collect();
        }
        foreach (explode(',', $request->query('with')) as $loadingProperty) {
            if (!$loadingProperty) continue;
            $flat->load($loadingProperty);
        }
        return $flat;
    });

    Route::get('/discussions', function (Request $request) {
        if (!$request->query('with')) {
            return $request->user()->discussions;
        }
        return $request->user()->discussions->load(explode(',', $request->query('with')));
    });

    Route::get('/discussions/{discussion}', function (Request $request, \App\Discussion $discussion) {
        if (!$discussion->participants->contains($request->user()->id)) {
            return collect();
        }
        foreach (explode(',', $request->query('with')) as $loadingProperty) {
            if (!$loadingProperty) continue;
            $discussion->load($loadingProperty);
        }
        return $discussion;
    });

    Route::get('/events', function (Request $request) {
        if (!in_array($request->query('type'), ['one_off', 'recurring']) ||
            !$request->query('from') || !$request->query('to')) {
            return collect();
        }
        $from = \Carbon\Carbon::createFromTimestampMs($request->query('from'), 'Europe/Brussels');
        $to = \Carbon\Carbon::createFromTimestampMs($request->query('to'), 'Europe/Brussels');

        if ($request->query('type') === 'one_off') {
            return $request->user()->events()->forFlat($request->query('flat_id'))
                ->oneOff()
                ->whereDate('start_date', '>=', $from)
                ->whereDate('start_date', '<=', $to)
                ->get();
        } else {
            return $request->user()->events()->forFlat($request->query('flat_id'))
                ->recurring()
                ->whereDate('start_date', '<=', $to)
                ->where(function ($q) use ($from) {
                    return $q->whereDate('end_date', '>', $from)->orWhereNull('end_date');
                })
                ->get();
        }
    });

    Route::post('/events', function (Request $request) {
        $event = new \App\Event;
        $event->label = $request->label;
        $event->flat_id = $request->flat_id;
        $event->category_id = $request->category_id;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->interval = $request->interval;
        if ($request->duration) {
            $event->duration = $request->duration;
        }
        $event->confirm = $request->confirm;
        $event->save();
        $event->participants()->attach($request->participants);

        return $event;
    });
});
