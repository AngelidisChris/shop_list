<?php

namespace App\Dto;

class ShopOwnerDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $password,
    ) {
    }
}