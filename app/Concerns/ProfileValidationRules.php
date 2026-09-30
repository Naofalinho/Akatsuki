<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Règles de validation du profil utilisateur.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),

            'email' => $this->emailRules($userId),

            'sexe' => $this->sexeRules(),

            'phone' => $this->phoneRules(),
        ];
    }


    /**
     * Règles de validation du nom.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return [
            'required',
            'string',
            'max:255',
        ];
    }


    /**
     * Règles de validation de l'adresse email.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',

            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }


    /**
     * Règles de validation du sexe.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function sexeRules(): array
    {
        return [
            'nullable',
            'string',
            Rule::in([
                'homme',
                'femme',
            ]),
        ];
    }


    /**
     * Règles de validation du numéro de téléphone.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function phoneRules(): array
    {
        return [
            'nullable',
            'string',
            'max:30',
        ];
    }
}

