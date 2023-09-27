<?php

namespace App\Dto;

class ShopCategoryDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
    ) {
    }
}