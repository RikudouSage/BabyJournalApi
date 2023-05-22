<?php

namespace App\Enum;

enum Scope: string
{
    case UserInfo = 'userInfo';
    case ChildInfo = 'childInfo';
    case Feeding = 'feeding';
    case Sleeping = 'sleeping';
    case Diapering = 'diapering';
    case Pumping = 'pumping';

    public function isRequired(): bool
    {
        return match ($this) {
            self::UserInfo, self::ChildInfo => true,
            default => false,
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::UserInfo => 'Basic user info like your name, your family name etc.',
            self::ChildInfo => 'Your children info, like their name, age, gender etc.',
            self::Feeding => 'Access to feeding data.',
            self::Sleeping => 'Access to sleeping data.',
            self::Diapering => 'Access to diapering data.',
            self::Pumping => 'Access to breast pumping data.',
        };
    }
}
