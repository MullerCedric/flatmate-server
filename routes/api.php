<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::post('/user/auth', function (Request $request) {
    $user = \App\User::all()
        ->where('email', $request->email)
        ->first();
    if ($user) {
        $user->makeVisible(['api_token', 'password']);
        if (Hash::check($request->password, $user->password)) {
            $user->makeHidden(['password']);
            $user['viewingFlat'] = $user->flats->sortByDesc('created_at')->pluck('id')->first();
            return $user;
        }
        abort(422, 'Mot de passe incorrect');
        return ['error' => 'Une erreur est survenue lors de la connexion'];
    } else {
        abort(422, 'E-mail incorrect');
        return ['error' => 'Une erreur est survenue lors de la connexion'];
    }
});

Route::post('/user', function (Request $request) {
    return \App\User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'api_token' => Str::random(80),
    ])->makeVisible(['api_token']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $user->makeVisible(['api_token']);
        $user['viewingFlat'] = $user->flats->sortByDesc('created_at')->pluck('id')->first();
        return $user;
    });

    Route::patch('/user', function (Request $request) {
        $user = $request->user();

        if (isset($request->profile['password'])) {
            $user->makeVisible(['password']);
            if (!Hash::check($request->profile['old_password'], $user->password)) {
                abort(422, 'Mot de passe incorrect');
                return ['error' => 'Une erreur est survenue lors de la modification du mot de passe'];
            }
            $user->password = Hash::make($request->profile['password']);
            $user->save();
            $user->makeHidden(['password']);
            return $user;
        }

        $user->update($request->profile);
        return $user;
    });

    Route::post('/user/avatar', function (Request $request) {
        if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            // $path = $request->avatar->store('avatars', 'public');
            $fileName = urlencode($request->avatar->getClientOriginalName());
            $fileNameFull = time() . '-' . $fileName;
            $path = 'img/avatars/' . $fileNameFull;
            $request->avatar->move('img/avatars', $fileNameFull);

            $user = $request->user();
            $user->update(['avatar' => $path]);

            return $path;
        } else {
            abort(422, 'Image corrompue');
            return ['error' => 'Une erreur est survenue lors de l\'upload de l\'image'];
        }
    });

    Route::get('/categories', function (Request $request) {
        if (!in_array($request->query('type'), ['events', 'transactions', 'notes'])) {
            return collect();
        }
        return $request->user()->categories->where('type', $request->query('type'));
    });

    Route::get('/flats', function (Request $request) {
        if (!$request->query('with')) {
            return $request->user()->flats->orderByDesc('created_at');
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
        $discussions = \App\Discussion::fromFlat($request->query('flat_id'))
            ->whereHas('participants', function ($query) use ($request) {
                $query->where('user_id', '=', $request->user()->id);
            })->get();
        foreach ($discussions as $discussion) {
            $discussion['latestMessage'] = \App\Message::where('discussion_id', $discussion['id'])
                ->latest()->orderBy('id', 'desc')->first();
        }
        if ($request->query('with')) {
            $discussions->load(explode(',', $request->query('with')));
        }

        return $discussions->sortByDesc(function ($item) {
            return $item->latestMessage->created_at;
        })->values()->all();
    });

    Route::post('/discussions', function (Request $request) {
        $discussion = new \App\Discussion;
        if ($request->label) {
            $discussion->label = $request->label;
        }
        $discussion->flat_id = $request->flat_id;
        $discussion->save();
        $discussion->participants()->attach($request->participants);
        $discussion->messages()->create([
            'type' => 'discussion',
            'content' => $request->startMessage,
        ]);
        $discussion['latestMessage'] = \App\Message::where('discussion_id', $discussion['id'])
            ->latest()->orderBy('id', 'desc')->first();

        return $discussion;
    });

    Route::get('/discussions/{discussion}', function (Request $request, \App\Discussion $discussion) {
        if (!$discussion->participants->contains($request->user()->id)) {
            return collect();
        }
        $offset = $request->query('offset') ? intval($request->query('offset'), 10) : 0;
        $limit = $request->query('limit') ? intval($request->query('limit'), 10) : 10;

        $messages = \App\Message::where('discussion_id', $discussion['id'])
            ->latest()->orderBy('id', 'desc')
            ->offset($offset)->limit($limit)->get();

        $messages->each(function ($message) use ($request) {
            $message->readBy()->syncWithoutDetaching($request->user()->id);
        });

        foreach (explode(',', $request->query('with')) as $loadingProperty) {
            if (!$loadingProperty) continue;
            $discussion->load($loadingProperty);
        }
        $discussion['messages'] = $messages;
        return $discussion;
    });

    Route::post('/discussions/{discussion}', function (Request $request, \App\Discussion $discussion) {
        if (!$discussion->participants->contains($request->user()->id)) {
            return collect();
        }

        $newMsg = $discussion->messages()->create([
            'from_id' => !$request->type || ($request->type && $request->type === 'message') ? $request->user()->id : null,
            'type' => $request->type ? $request->type : 'message',
            'content' => $request->message,
        ]);
        $newMsg->readBy()->syncWithoutDetaching($request->user()->id);

        $msg = \App\Message::findOrFail($newMsg->id);

        broadcast(new \App\Events\MessageCreated($msg))->toOthers();

        return $msg;
    });

    Route::get('/events', function (Request $request) {
        if (!in_array($request->query('type'), ['one_off', 'recurring']) ||
            !$request->query('from') || !$request->query('to')) {
            return collect();
        }
        $from = \Carbon\Carbon::createFromTimestampMs($request->query('from'), 'Europe/Brussels');
        $to = \Carbon\Carbon::createFromTimestampMs($request->query('to'), 'Europe/Brussels');

        if ($request->query('type') === 'one_off') {
            return \App\Event::oneOff()
                ->where(function ($q) use ($request) {
                    $q
                        ->where('flat_id', $request->query('flat_id'))
                        ->orWhere(function ($r) use ($request) {
                            $r
                                ->whereNull('flat_id')
                                ->whereHas('participants', function ($s) use ($request) {
                                    $s->where('user_id', '=', $request->user()->id);
                                });
                        });
                })
                ->whereDate('start_date', '>=', $from)
                ->whereDate('start_date', '<=', $to)
                ->get();
        } else {
            return \App\Event::recurring()
                ->where(function ($q) use ($request) {
                    $q
                        ->where('flat_id', $request->query('flat_id'))
                        ->orWhere(function ($r) use ($request) {
                            $r
                                ->whereNull('flat_id')
                                ->whereHas('participants', function ($s) use ($request) {
                                    $s->where('user_id', '=', $request->user()->id);
                                });
                        });
                })
                ->whereDate('start_date', '<=', $to)
                ->where(function ($q) use ($from) {
                    return $q->whereDate('end_date', '>', $from)->orWhereNull('end_date');
                })
                ->get();
        }
    });

    Route::post('/events', function (Request $request) {
        $event = \App\Event::updateOrCreate(
            ['id' => request('id') ?? null],
            [
                'label' => request('label'),
                'flat_id' => request('flat_id'),
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'interval' => request('interval'),
                'duration' => request('duration') ?? 3600000,
                'confirm' => request('confirm'),
            ]
        );
        $event->categories()->detach(\App\Category::all()->pluck('id'));
        $event->categories()->attach($request->category_id);
        $event->participants()->sync($request->participants);

        return \App\Event::findOrFail($event->id);
    });

    Route::delete('/events/{event}', function (Request $request, \App\Event $event) {
        $event->delete();
        return json_encode(['response' => 'Event deleted']);
    });

    Route::get('/events/{event}/confirmations', function (Request $request, \App\Event $event) {
        $evRpIn = \Carbon\Carbon::parse($request->query('event_repeat_instance'))->setTimezone('UTC');

        return $event->confirmedBy()
            ->wherePivot('event_repeat_instance', $evRpIn)->get();
    });

    Route::post('/events/{event}/confirmations', function (Request $request, \App\Event $event) {
        if (!$event->participants->contains($request->user()->id)) {
            return $event->confirmedBy()->get();
        }
        $evRpIn = \Carbon\Carbon::parse($request->event_repeat_instance)->setTimezone('UTC');

        $event->confirmedBy()->attach($request->user()->id, [
            'is_accepted' => $request->is_accepted,
            'event_repeat_instance' => $evRpIn,
        ]);

        return $event->confirmedBy()
            ->wherePivot('event_repeat_instance', $evRpIn)->get();
    });
});
