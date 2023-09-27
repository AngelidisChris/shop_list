<?php

namespace App\Dto;


class ShopDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?ShopCategoryDTO $shopCategory,
        public readonly ?string $description,
        public readonly ?array $openHours,
        public readonly ?string $city,
        public readonly ?string $address,
    ) {
    }
}