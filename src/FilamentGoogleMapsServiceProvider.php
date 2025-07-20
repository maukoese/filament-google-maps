<?php

namespace Cheesegrits\FilamentGoogleMaps;

use Cheesegrits\FilamentGoogleMaps\Commands\ModelCode;
use Cheesegrits\FilamentGoogleMaps\Commands\GeocodeTable;
use Cheesegrits\FilamentGoogleMaps\Commands\Geocode;
use Cheesegrits\FilamentGoogleMaps\Commands\ReverseGeocodeTable;
use Cheesegrits\FilamentGoogleMaps\Commands\ReverseGeocode;
use Cheesegrits\FilamentGoogleMaps\Commands\MakeWidgetCommand;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapWidget;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentGoogleMapsServiceProvider extends PackageServiceProvider
{
    protected array $widgets = [
        MapWidget::class,
        MapTableWidget::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-google-maps')
            ->hasCommands($this->getCommands())
            ->hasConfigFile()
            ->hasRoutes(['web'])
            ->hasTranslations()
            ->hasViews();
    }

    protected function getCommands(): array
    {
        $commands = [
            ModelCode::class,
            GeocodeTable::class,
            Geocode::class,
            ReverseGeocodeTable::class,
            ReverseGeocode::class,
            MakeWidgetCommand::class,
        ];

        $aliases = [];

        foreach ($commands as $command) {
            $class = 'Cheesegrits\\FilamentGoogleMaps\\Commands\\Aliases\\' . class_basename($command);

            if (!class_exists($class)) {
                continue;
            }

            $aliases[] = $class;
        }

        return array_merge($commands, $aliases);
    }

    public function packageRegistered()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-google-maps.php', 'filament-google-maps');
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('filament-google-maps-geocomplete', __DIR__ . '/../dist/maukoese/filament-google-maps/filament-google-geocomplete.js'),
            AlpineComponent::make('filament-google-maps-field', __DIR__ . '/../dist/maukoese/filament-google-maps/filament-google-maps.js'),
            AlpineComponent::make('filament-google-maps-widget', __DIR__ . '/../dist/maukoese/filament-google-maps/filament-google-maps-widget.js'),
            AlpineComponent::make('filament-google-maps-entry', __DIR__ . '/../dist/maukoese/filament-google-maps/filament-google-maps-entry.js'),
        ], 'maukoese/filament-google-maps');
    }
}
