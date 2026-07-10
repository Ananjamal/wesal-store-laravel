<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function getHeading(): string
    {
        return __('profile.edit_profile');
    }

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('profile.personal_info'))
                    ->description(__('profile.personal_info_desc'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                FileUpload::make('avatar')
                                    ->label(__('profile.avatar'))
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->directory('avatars')
                                    ->columnSpan([
                                        'default' => 3,
                                        'md' => 1,
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('field.name') ?? 'الاسم')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label(__('field.email') ?? 'البريد الإلكتروني')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique('users', 'email', ignorable: auth()->user()),
                                        TextInput::make('phone')
                                            ->label(__('profile.phone'))
                                            ->tel()
                                            ->maxLength(255),
                                    ])
                                    ->columnSpan([
                                        'default' => 3,
                                        'md' => 2,
                                    ]),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('profile.change_password'))
                    ->description(__('profile.change_password_desc'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('كلمة المرور الحالية')
                                    ->password()
                                    ->revealable()
                                    ->required(fn ($get) => filled($get('new_password'))),
                                TextInput::make('new_password')
                                    ->label(__('profile.change_password'))
                                    ->password()
                                    ->revealable()
                                    ->rule(Password::default()),
                                TextInput::make('new_password_confirmation')
                                    ->label('تأكيد كلمة المرور الجديدة')
                                    ->password()
                                    ->revealable()
                                    ->same('new_password')
                                    ->required(fn ($get) => filled($get('new_password'))),
                            ]),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ التغييرات')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $user = auth()->user();
        $data = $this->form->getState();

        // Validate current password if changing password
        if (filled($data['new_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                $this->addError('data.current_password', 'كلمة المرور الحالية غير صحيحة.');
                return;
            }
            $user->password = Hash::make($data['new_password']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->avatar = $data['avatar'];
        $user->save();

        Notification::make()
            ->title('تم حفظ البيانات بنجاح')
            ->success()
            ->send();
    }
}
