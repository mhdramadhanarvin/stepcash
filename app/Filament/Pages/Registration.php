<?php

namespace App\Filament\Pages;

use App\Enums\PartnerEnum;
use App\Filament\Resources\PartnerResource;
use App\Models\Partner;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;

class Registration extends Register
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Akun')
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                    Wizard\Step::make('Informasi Toko')
                        ->schema([
                            TextInput::make('name_partner')->label('Nama Toko')->unique(table: Partner::class, column: 'name'),
                            TextInput::make('address')->label('Alamat Toko'),
                            Select::make('sector')->label('Sektor')->options(PartnerEnum::class),
                        ])
                ])
            ]);
    }

    public function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $user->partner()->create([
            'name' => $data['name_partner'],
            'address' => $data['address'],
            'sector' => $data['sector'],
            'is_active' => false
        ]);
        $user->assignRole('partner');
        Notification::make()
            ->info()
            ->title('Mitra Baru')
            ->body('Ada mitra baru mendaftar, segera verifikasi data mitra tersebut!')
            ->actions([
                ActionsAction::make('lihat')
                    ->button()
                    ->url(PartnerResource::getUrl('index')),
            ])
            ->sendToDatabase(User::find(1));

        return $user;
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }
}
