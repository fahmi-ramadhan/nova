<?php

namespace Readit\NytBestsellers;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NytBestsellers extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     */
    public function boot(): void
    {
        Nova::mix('nyt-bestsellers', __DIR__ . '/../dist/mix-manifest.json');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     */
    public function menu(Request $request): MenuSection
    {
        return MenuSection::make('Nyt Bestsellers')
            ->path('/nyt-bestsellers')
            ->icon('newspaper');
    }
}
