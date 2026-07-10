<?php

namespace App\Filament\Pages;

use App\Models\StoreSetting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Manager']);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource.store_settings');
    }

    public function getHeading(): string
    {
        return __('resource.store_settings');
    }

    public function mount(): void
    {
        $this->form->fill([
            'store_name' => StoreSetting::getValue('store_name', 'وصال'),
            'store_email' => StoreSetting::getValue('store_email', 'info@wisal-store.com'),
            'store_phone' => StoreSetting::getValue('store_phone', ''),
            'store_address' => StoreSetting::getValue('store_address', ''),
            'store_logo' => StoreSetting::getValue('store_logo'),
            'store_favicon' => StoreSetting::getValue('store_favicon'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('settings.identity_design'))
                    ->description(__('settings.identity_design_desc'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('store_name')
                                    ->label(__('settings.store_name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('store_email')
                                    ->label(__('settings.store_email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('store_phone')
                                    ->label(__('settings.store_phone'))
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('store_address')
                                    ->label(__('settings.store_address'))
                                    ->maxLength(255),
                                FileUpload::make('store_logo')
                                    ->label(__('settings.store_logo'))
                                    ->image()
                                    ->directory('settings')
                                    ->columnSpan(1),
                                FileUpload::make('store_favicon')
                                    ->label(__('settings.store_favicon'))
                                    ->image()
                                    ->directory('settings')
                                    ->columnSpan(1),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('settings.save_changes'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save brand settings
        StoreSetting::setValue('store_name', $data['store_name'], 'string');
        StoreSetting::setValue('store_email', $data['store_email'], 'string');
        StoreSetting::setValue('store_phone', $data['store_phone'] ?? '', 'string');
        StoreSetting::setValue('store_address', $data['store_address'] ?? '', 'string');
        StoreSetting::setValue('store_logo', $data['store_logo'], 'string');
        StoreSetting::setValue('store_favicon', $data['store_favicon'], 'string');

        Notification::make()
            ->title(__('settings.saved_successfully'))
            ->success()
            ->send();
    }
}
