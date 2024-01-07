<?php

namespace App\Services;

interface ApiProviderInterface {
    public function getApiData(string $endPoint): array;
}
