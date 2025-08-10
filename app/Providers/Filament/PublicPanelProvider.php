<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;
use App\Filament\Components\SubmissionViewer;

class PublicPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Livewire::component('submission-viewer', SubmissionViewer::class);
        
        return $panel
            ->id('public')
            ->path('responder')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->pages([
                \App\Filament\Pages\ListActiveForms::class,
                \App\Filament\Pages\AnswerForm::class,
                \App\Filament\Pages\MySubmissions::class,
                \App\Filament\Pages\ViewSubmission::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->defaultAvatarProvider(\Filament\AvatarProviders\UiAvatarsProvider::class);
    }
}
