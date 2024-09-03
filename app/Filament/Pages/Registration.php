<?php

namespace App\Filament\Pages;

use App\Enums\PartnerEnum;
use App\Models\Partner;
use App\Repositories\PartnerRepositoryInterface;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\Auth\Register;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Registration extends Register
{
    public $is_valid;

    /*protected function __construct(PartnerRepositoryInterface $partnerRepository)*/
    /*{*/
    /*    $this->partnerRepository = $partnerRepository;*/
    /*}*/

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
            'sector' => $data['sector'],
            'is_active' => false
        ]);
        $user->assignRole('partner');

        return $user;
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }
}
