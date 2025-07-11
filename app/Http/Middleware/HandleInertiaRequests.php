<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'appName' => config('app.name'),

            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },

            'auth' => ['user' => fn() => $request->user()
                ? $request->user()->only('id', 'name')
                : null,
                'isAuthenticated' => fn() => $request->user() !== null,
                'isAdmin' => fn() => $request->user() && $request->user()->isAdmin(),
            ],

            'restaurant' => config('restaurant'),

            'flash' => fn() => [
                'message' => $request->session()->get('flash.message'),
                'type' => $request->session()->get('flash.type', 'success'),
            ],
        ]);
    }
}
