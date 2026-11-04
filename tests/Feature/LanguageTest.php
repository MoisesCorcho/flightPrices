<?php

use Illuminate\Support\Facades\App;

test('default application locale is spanish', function () {
    expect(config('app.locale'))->toBe('es')
        ->and(config('app.fallback_locale'))->toBe('es');

    $this->get('/');
    expect(App::getLocale())->toBe('es');
});

test('user can switch application language to english and back', function () {
    $response = $this->get(route('language.switch', 'en'));

    $response->assertRedirect();
    $response->assertSessionHas('locale', 'en');

    $this->get('/');
    expect(App::getLocale())->toBe('en');

    $responseEs = $this->get(route('language.switch', 'es'));
    $responseEs->assertRedirect();
    $responseEs->assertSessionHas('locale', 'es');

    $this->get('/');
    expect(App::getLocale())->toBe('es');
});

test('switching to unsupported locale does not change current locale', function () {
    $response = $this->get(route('language.switch', 'fr'));

    $response->assertRedirect();
    $response->assertSessionMissing('locale');
});
