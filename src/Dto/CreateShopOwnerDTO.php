<?php

namespace App\Dto;

class CreateShopOwnerDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $password,
    ) {
    }
}